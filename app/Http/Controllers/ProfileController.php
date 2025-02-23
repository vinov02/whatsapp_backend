<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Chat;
use App\Models\Status;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProfileController extends Controller {
    public function update(Request $request)
    {
        $user = Auth::user(); // Get authenticated user

        $request->validate([
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6'
        ]);

        $user->update([
            'name' => $request->name ?? $user->name,
            'email' => $request->email ?? $user->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password
        ]);

        return response()->json(['message' => 'Profile updated successfully', 'user' => $user]);
    }
    public function show()
    {
        return response()->json(['user' => Auth::user()]);
    }
    public function toggleGhostMode()
{
    $user = Auth::user();
    $user->ghost_mode = !$user->ghost_mode;
    $user->save();

    return response()->json([
        'message' => 'Ghost mode ' . ($user->ghost_mode ? 'enabled' : 'disabled'),
        'status' => $user->ghost_mode
    ]);
}

}