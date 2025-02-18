<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Chat;
use App\Models\Status;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class ChatController extends Controller {
    public function sendMessage(Request $request) {
        Chat::create($request->all());
        return response()->json(['message' => 'Message sent']);
    }

    public function chatHistory() {
        return response()->json(Chat::all());
    }

    public function selfDestructMessage(Request $request) {
        Chat::where('id', $request->id)->delete();
        return response()->json(['message' => 'Message deleted']);
    }
}