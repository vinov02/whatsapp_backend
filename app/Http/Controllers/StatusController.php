<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Chat;
use App\Models\Status;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class StatusController extends Controller {
    public function updateStatus(Request $request)
{
    $request->validate([
        'status_text' => 'required|string|max:255'
    ]);

    $status = Status::updateOrCreate(
        ['user_id' => Auth::id()],
        ['status_text' => $request->status_text]
    );

    return response()->json(['message' => 'Status updated', 'status' => $status]);
}
public function viewStatus()
{
    $statuses = Status::all();
    return response()->json(['statuses' => $statuses]);
}
}
