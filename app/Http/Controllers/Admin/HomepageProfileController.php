<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CompanionProfile;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class HomepageProfileController extends Controller
{
    // Write audit log helper
    protected function logAction($action, $description)
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Recommended Profiles Management Page
     */
    public function recommended(Request $request)
    {
        // 1. Current Recommended Profiles (sorted by order)
        $recommended = User::where('role', 'partner')
            ->whereHas('companionProfile', function ($q) {
                $q->where('is_recommended', true);
            })
            ->with(['companionProfile', 'city'])
            ->join('companion_profiles', 'users.id', '=', 'companion_profiles.user_id')
            ->select('users.*', 'companion_profiles.rating', 'companion_profiles.recommended_order', 'companion_profiles.is_recommended_visible', 'companion_profiles.kyc_status')
            ->orderBy('companion_profiles.recommended_order', 'asc')
            ->get();

        // 2. Search/Add Panel query (all companions NOT currently recommended)
        $query = User::where('role', 'partner')
            ->whereHas('companionProfile', function ($q) {
                $q->where('is_recommended', false);
            })
            ->with(['companionProfile', 'city']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%")
                  ->orWhere('users.id', '=', str_ireplace('PT-', '', $search))
                  ->orWhere('users.id', '=', $search);
            });
        }

        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'active') {
                $query->where('users.is_active', true);
            } elseif ($status === 'blocked') {
                $query->where('users.is_active', false);
            } elseif ($status === 'approved_kyc') {
                $query->whereHas('companionProfile', function($cpq) {
                    $cpq->where('kyc_status', 'approved');
                });
            } elseif ($status === 'pending_kyc') {
                $query->whereHas('companionProfile', function($cpq) {
                    $cpq->where('kyc_status', 'pending');
                });
            }
        }

        $companions = $query->latest('users.created_at')->paginate(10)->withQueryString();

        return view('admin.homepage.recommended', compact('recommended', 'companions'));
    }

    /**
     * Add profile to recommended list
     */
    public function addRecommended(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = User::where('role', 'partner')->findOrFail($request->user_id);
        $profile = $user->companionProfile;

        // Get max order index currently
        $maxOrder = CompanionProfile::where('is_recommended', true)->max('recommended_order') ?? 0;

        $profile->is_recommended = true;
        $profile->recommended_order = $maxOrder + 1;
        $profile->is_recommended_visible = true;
        $profile->save();

        $this->logAction('HOMEPAGE_RECOMMENDED_ADD', "Added companion {$user->name} (PT-{$user->id}) to Recommended Profiles");

        return back()->with('success', "{$user->name} has been added to Recommended Profiles.");
    }

    /**
     * Remove profile from recommended list
     */
    public function removeRecommended($id)
    {
        $user = User::where('role', 'partner')->findOrFail($id);
        $profile = $user->companionProfile;

        $profile->is_recommended = false;
        $profile->recommended_order = 0;
        $profile->save();

        $this->logAction('HOMEPAGE_RECOMMENDED_REMOVE', "Removed companion {$user->name} (PT-{$user->id}) from Recommended Profiles");

        return back()->with('success', "{$user->name} has been removed from Recommended Profiles.");
    }

    /**
     * Toggle recommended visibility
     */
    public function toggleRecommendedVisibility($id)
    {
        $user = User::where('role', 'partner')->findOrFail($id);
        $profile = $user->companionProfile;

        $profile->is_recommended_visible = !$profile->is_recommended_visible;
        $profile->save();

        $status = $profile->is_recommended_visible ? 'enabled' : 'disabled';
        $this->logAction('HOMEPAGE_RECOMMENDED_VISIBILITY', "Toggled Recommended Profile visibility for {$user->name} (PT-{$user->id}) to {$status}");

        return back()->with('success', "Homepage visibility of Recommended Profile for {$user->name} set to {$status}.");
    }

    /**
     * AJAX drag & drop reorder Recommended Profiles
     */
    public function reorderRecommended(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:users,id'
        ]);

        foreach ($request->ids as $index => $id) {
            CompanionProfile::where('user_id', $id)->update([
                'recommended_order' => $index + 1
            ]);
        }

        $this->logAction('HOMEPAGE_RECOMMENDED_REORDER', 'Reordered Recommended Profiles display sequence');

        return response()->json(['success' => true]);
    }

    /**
     * Top Profiles Management Page
     */
    public function top(Request $request)
    {
        // 1. Current Top Profiles (sorted by order)
        $top = User::where('role', 'partner')
            ->whereHas('companionProfile', function ($q) {
                $q->where('is_top_profile', true);
            })
            ->with(['companionProfile', 'city'])
            ->join('companion_profiles', 'users.id', '=', 'companion_profiles.user_id')
            ->select('users.*', 'companion_profiles.rating', 'companion_profiles.top_profile_order', 'companion_profiles.is_top_profile_visible', 'companion_profiles.kyc_status')
            ->orderBy('companion_profiles.top_profile_order', 'asc')
            ->get();

        // 2. Search/Add Panel query (all companions NOT currently top profiles)
        $query = User::where('role', 'partner')
            ->whereHas('companionProfile', function ($q) {
                $q->where('is_top_profile', false);
            })
            ->with(['companionProfile', 'city']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%")
                  ->orWhere('users.id', '=', str_ireplace('PT-', '', $search))
                  ->orWhere('users.id', '=', $search);
            });
        }

        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'active') {
                $query->where('users.is_active', true);
            } elseif ($status === 'blocked') {
                $query->where('users.is_active', false);
            } elseif ($status === 'approved_kyc') {
                $query->whereHas('companionProfile', function($cpq) {
                    $cpq->where('kyc_status', 'approved');
                });
            } elseif ($status === 'pending_kyc') {
                $query->whereHas('companionProfile', function($cpq) {
                    $cpq->where('kyc_status', 'pending');
                });
            }
        }

        $companions = $query->latest('users.created_at')->paginate(10)->withQueryString();

        return view('admin.homepage.top', compact('top', 'companions'));
    }

    /**
     * Add profile to top list
     */
    public function addTop(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = User::where('role', 'partner')->findOrFail($request->user_id);
        $profile = $user->companionProfile;

        // Get max order index currently
        $maxOrder = CompanionProfile::where('is_top_profile', true)->max('top_profile_order') ?? 0;

        $profile->is_top_profile = true;
        $profile->top_profile_order = $maxOrder + 1;
        $profile->is_top_profile_visible = true;
        $profile->save();

        $this->logAction('HOMEPAGE_TOP_ADD', "Added companion {$user->name} (PT-{$user->id}) to Top Profiles");

        return back()->with('success', "{$user->name} has been added to Top Profiles.");
    }

    /**
     * Remove profile from top list
     */
    public function removeTop($id)
    {
        $user = User::where('role', 'partner')->findOrFail($id);
        $profile = $user->companionProfile;

        $profile->is_top_profile = false;
        $profile->top_profile_order = 0;
        $profile->save();

        $this->logAction('HOMEPAGE_TOP_REMOVE', "Removed companion {$user->name} (PT-{$user->id}) from Top Profiles");

        return back()->with('success', "{$user->name} has been removed from Top Profiles.");
    }

    /**
     * Toggle top visibility
     */
    public function toggleTopVisibility($id)
    {
        $user = User::where('role', 'partner')->findOrFail($id);
        $profile = $user->companionProfile;

        $profile->is_top_profile_visible = !$profile->is_top_profile_visible;
        $profile->save();

        $status = $profile->is_top_profile_visible ? 'enabled' : 'disabled';
        $this->logAction('HOMEPAGE_TOP_VISIBILITY', "Toggled Top Profile visibility for {$user->name} (PT-{$user->id}) to {$status}");

        return back()->with('success', "Homepage visibility of Top Profile for {$user->name} set to {$status}.");
    }

    /**
     * AJAX drag & drop reorder Top Profiles
     */
    public function reorderTop(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:users,id'
        ]);

        foreach ($request->ids as $index => $id) {
            CompanionProfile::where('user_id', $id)->update([
                'top_profile_order' => $index + 1
            ]);
        }

        $this->logAction('HOMEPAGE_TOP_REORDER', 'Reordered Top Profiles display sequence');

        return response()->json(['success' => true]);
    }
}
