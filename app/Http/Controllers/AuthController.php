<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'online'
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'message' => 'Registration successful'
        ], 201);
    }

    public function login(Request $request)
{
    // Validate request
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation error',
            'errors' => $validator->errors()
        ], 422);
    }

    // Attempt login
    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json([
            'message' => 'Invalid login credentials'
        ], 401);
    }

    // Get authenticated user
    $user = Auth::user();

    // Generate token
    $token = $user->createToken('auth_token')->plainTextToken;

    // Update user status to 'online'
    $user->update(['status' => 'online']);

    return response()->json([
        'user' => $user,
        'token' => $token,
        'message' => 'Login successful'
    ]);
}

    public function logout(Request $request)
    {
        $request->user()->update(['status' => 'offline']);
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric'
        ]);

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Check if OTP matches (assuming OTP is stored in the 'otp' column in users table)
        if ($user->otp !== $request->otp) {
            return response()->json(['message' => 'Invalid OTP'], 401);
        }

        // OTP verified, generate access token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Clear the OTP after verification (optional)
        $user->otp = null;
        $user->save();

        return response()->json([
            'message' => 'OTP verified successfully',
            'user' => $user,
            'token' => $token
        ], 200);
    }
    public function sendOtp(Request $request)
{
    $request->validate([
        'email' => 'required|email'
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    // Generate a random 6-digit OTP
    $otp = rand(100000, 999999);

    // Store OTP in the database
    $user->otp = $otp;
    $user->save();

    // Optionally, send OTP via email
    Mail::to($user->email)->send(new OtpMail($otp));

    return response()->json(['message' => 'OTP sent successfully'], 200);
}
}