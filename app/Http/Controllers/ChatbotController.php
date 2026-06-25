<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ChatbotMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;

class ChatbotController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userMessage = $request->input('message');
        
        $userId = Auth::id();
        $sessionId = session()->getId();

        // Save user message
        ChatbotMessage::create([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'role' => 'user',
            'content' => $userMessage,
        ]);

        // Get chat history for context (last 10 messages)
        $history = ChatbotMessage::where(function($query) use ($userId, $sessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get()
        ->reverse();

        $messages = [
            [
                'role' => 'system',
                'content' => 'You are a professional, helpful customer support assistant for the Antigravity platform (a Companion Booking Platform). Help users with login issues, account problems, navigation, errors, and suggestions. Provide step-by-step solutions in simple language. If unsure, ask clarifying questions. Be polite, helpful, and professional.'
            ]
        ];

        foreach ($history as $msg) {
            $messages[] = [
                'role' => $msg->role,
                'content' => $msg->content,
            ];
        }

        try {
            $result = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => $messages,
                'max_tokens' => 500,
            ]);

            $aiResponse = $result->choices[0]->message->content ?? "Sorry, I couldn't process your request right now.";

            // Save AI response
            ChatbotMessage::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'role' => 'assistant',
                'content' => $aiResponse,
            ]);

            return response()->json([
                'reply' => $aiResponse
            ]);

        } catch (\Exception $e) {
            Log::error('Chatbot Error: ' . $e->getMessage());
            
            $fallbackMsg = "Sorry, I couldn't process your request right now.";
            
            // Save fallback response so it persists on refresh
            ChatbotMessage::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'role' => 'assistant',
                'content' => $fallbackMsg,
            ]);

            return response()->json([
                'reply' => $fallbackMsg
            ]);
        }
    }

    public function history(Request $request)
    {
        $userId = Auth::id();
        $sessionId = session()->getId();

        $history = ChatbotMessage::where(function($query) use ($userId, $sessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })
        ->orderBy('created_at', 'asc')
        ->get(['role', 'content']);

        return response()->json([
            'history' => $history
        ]);
    }
}
