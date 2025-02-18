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
    public function updateStatus(Request $request) {
        Status::create($request->all());
        return response()->json(['message' => 'Status updated']);
    }

    public function viewStatus() {
        return response()->json(Status::all());
    }
}
