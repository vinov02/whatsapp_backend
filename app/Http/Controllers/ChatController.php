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
    public function sendMessage(Request $request)
{
    $request->validate([
        'receiver_id' => 'required|exists:users,id',
        'message' => 'required|string'
    ]);

    $chat = Chat::create([
        'sender_id' => Auth::id(),
        'receiver_id' => $request->receiver_id,
        'message' => $request->message
    ]);

    return response()->json(['message' => 'Message sent', 'chat' => $chat]);
}


public function chatHistory()
{
    $userId = Auth::id();
    $chats = Chat::where('sender_id', $userId)
                 ->orWhere('receiver_id', $userId)
                 ->orderBy('created_at', 'desc')
                 ->get();

    return response()->json(['chats' => $chats]);
}


public function selfDestructMessage(Request $request)
{
    $request->validate([
        'message_id' => 'required|exists:chats,id'
    ]);

    Chat::where('id', $request->message_id)
        ->where('sender_id', Auth::id())
        ->delete();

    return response()->json(['message' => 'Chat message deleted']);
}

}