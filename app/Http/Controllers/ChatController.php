<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    // Ensure the user has an approved/completed/ongoing/paid booking with the other party
    private function canMessage($customer_id, $partner_id)
    {
        return Booking::where('customer_id', $customer_id)
            ->where('partner_id', $partner_id)
            ->whereIn('status', ['approved', 'ongoing', 'completed', 'paid', 'confirmed', 'rescheduled'])
            ->exists();
    }

    public function getConversations()
    {
        $user = Auth::user();
        
        $conversations = Conversation::where('customer_id', $user->id)
            ->orWhere('companion_id', $user->id)
            ->with(['customer', 'companion'])
            ->orderBy('last_message_at', 'desc')
            ->get();

        $data = $conversations->map(function ($conv) use ($user) {
            $otherUser = $conv->customer_id == $user->id ? $conv->companion : $conv->customer;
            
            $lastMsg = $conv->messages()->latest()->first();
            $unreadCount = $conv->messages()
                ->where('receiver_id', $user->id)
                ->where('is_read', false)
                ->count();

            return [
                'id' => $conv->id,
                'other_user' => [
                    'id' => $otherUser->id,
                    'name' => $otherUser->name,
                    'avatar' => $otherUser->profile_picture_url ?? null,
                ],
                'last_message' => $lastMsg ? $lastMsg->message ?? ($lastMsg->attachment_type ? ucfirst($lastMsg->attachment_type) : 'Attachment') : null,
                'last_message_time' => $lastMsg ? $lastMsg->created_at->diffForHumans() : null,
                'unread_count' => $unreadCount,
            ];
        });

        return response()->json(['conversations' => $data]);
    }

    public function getMessages($conversation_id, Request $request)
    {
        $user = Auth::user();
        $conversation = Conversation::findOrFail($conversation_id);

        if ($conversation->customer_id != $user->id && $conversation->companion_id != $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $query = $conversation->messages()->with(['sender']);
        
        if ($request->has('last_id')) {
            $query->where('id', '>', $request->last_id);
        }

        $messages = $query->orderBy('created_at', 'asc')->get();

        $data = $messages->map(function ($msg) use ($user) {
            return [
                'id' => $msg->id,
                'is_own' => $msg->sender_id == $user->id,
                'message' => $msg->message,
                'attachment_url' => $msg->attachment_path ? Storage::url($msg->attachment_path) : null,
                'attachment_type' => $msg->attachment_type,
                'original_filename' => $msg->original_filename,
                'time' => $msg->created_at->format('h:i A'),
                'is_read' => $msg->is_read
            ];
        });

        return response()->json(['messages' => $data]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,webp,pdf|max:5120'
        ]);

        if (!$request->filled('message') && !$request->hasFile('attachment')) {
            return response()->json(['error' => 'Message or attachment is required'], 422);
        }

        $user = Auth::user();
        $receiverId = $request->receiver_id;

        $customerId = $user->role === 'customer' ? $user->id : $receiverId;
        $companionId = $user->role === 'partner' ? $user->id : $receiverId;

        // Check Access
        if (!$this->canMessage($customerId, $companionId)) {
            return response()->json(['error' => 'You must have an approved booking to chat.'], 403);
        }

        // Find or create conversation
        $conversation = Conversation::firstOrCreate(
            ['customer_id' => $customerId, 'companion_id' => $companionId],
            ['last_message_at' => now()]
        );

        if ($conversation->is_blocked) {
            return response()->json(['error' => 'This conversation is blocked.'], 403);
        }

        $attachmentPath = null;
        $attachmentType = null;
        $originalName = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $originalName = $file->getClientOriginalName();
            $ext = strtolower($file->getClientOriginalExtension());
            $attachmentType = $ext === 'pdf' ? 'pdf' : 'image';
            $attachmentPath = $file->store('chat_attachments', 'public');
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'message' => $request->message,
            'attachment_path' => $attachmentPath,
            'attachment_type' => $attachmentType,
            'original_filename' => $originalName,
            'is_read' => false
        ]);

        $conversation->update(['last_message_at' => now()]);

        return response()->json([
            'success' => true, 
            'message' => [
                'id' => $message->id,
                'is_own' => true,
                'message' => $message->message,
                'attachment_url' => $message->attachment_path ? Storage::url($message->attachment_path) : null,
                'attachment_type' => $message->attachment_type,
                'original_filename' => $message->original_filename,
                'time' => $message->created_at->format('h:i A'),
                'is_read' => $message->is_read
            ],
            'conversation_id' => $conversation->id
        ]);
    }

    public function markRead(Request $request, $conversation_id)
    {
        $user = Auth::user();
        
        Message::where('conversation_id', $conversation_id)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function globalUnread()
    {
        $user = Auth::user();
        if (!$user) return response()->json(['unread' => 0]);

        $unread = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

        return response()->json(['unread' => $unread]);
    }

    public function startConversation($user_id)
    {
        $user = Auth::user();
        
        $customerId = $user->role === 'customer' ? $user->id : $user_id;
        $partnerId = $user->role === 'partner' ? $user->id : $user_id;

        if (!$this->canMessage($customerId, $partnerId)) {
            return redirect()->back()->with('error', 'You must have an approved or completed booking to chat.');
        }

        $conversation = Conversation::firstOrCreate(
            ['customer_id' => $customerId, 'companion_id' => $partnerId],
            ['last_message_at' => now()]
        );

        if ($user->role === 'customer') {
            return redirect()->route('customer.messages', ['c' => $conversation->id]);
        } else {
            return redirect()->route('partner.messages', ['c' => $conversation->id]);
        }
    }
}
