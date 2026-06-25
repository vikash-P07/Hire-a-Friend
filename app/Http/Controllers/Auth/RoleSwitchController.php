<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleSwitchController extends Controller
{
    /**
     * Switch the active session role.
     */
    public function switchRole(Request $request)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = Auth::user();
        $targetRole = $request->role;

        if (!$user->hasRole($targetRole)) {
            return back()->withErrors(['role' => 'You do not have permission to switch to this role.']);
        }

        session(['active_role' => $targetRole]);

        // Redirect based on active role
        if ($targetRole === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', "Switched active role to Admin.");
        } elseif ($targetRole === 'partner') {
            return redirect()->route('partner.dashboard')->with('success', "Switched active role to Partner.");
        }

        return redirect()->route('customer.dashboard')->with('success', "Switched active role to Customer.");
    }

    /**
     * API: Get all roles of current authenticated user.
     */
    public function getUserRoles()
    {
        $user = Auth::user();
        return response()->json([
            'assigned_roles' => $user->roles->pluck('name'),
            'active_role' => $user->getActiveRole()
        ]);
    }

    /**
     * API: Switch active role.
     */
    public function switchActiveRole(Request $request)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = Auth::user();
        $targetRole = $request->role;

        if (!$user->hasRole($targetRole)) {
            return response()->json(['error' => 'Unauthorized role assignment.'], 403);
        }

        session(['active_role' => $targetRole]);

        return response()->json([
            'message' => 'Role switched successfully.',
            'active_role' => $targetRole
        ]);
    }

    /**
     * API: Assign a role to a user.
     */
    public function assignRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->assignRole($request->role);

        return response()->json([
            'message' => 'Role assigned successfully.',
            'user_roles' => $user->roles->pluck('name')
        ]);
    }

    /**
     * API: Remove a role from a user.
     */
    public function removeRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->removeRole($request->role);

        return response()->json([
            'message' => 'Role removed successfully.',
            'user_roles' => $user->roles->pluck('name')
        ]);
    }
}
