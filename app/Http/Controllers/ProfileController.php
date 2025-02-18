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
    public function update(Request $request) {
        Auth::user()->update($request->all());
        return response()->json(['message' => 'Profile updated successfully']);
    }

    public function show() {
        return response()->json(Auth::user());
    }

    public function toggleGhostMode() {
        $user = Auth::user();
        $user->ghost_mode = !$user->ghost_mode;
        $user->save();
        return response()->json(['ghost_mode' => $user->ghost_mode]);
    }
}