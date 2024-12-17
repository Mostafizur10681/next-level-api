<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Exception;

class AuthController extends Controller
{
    //User Registration
    public function register(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'name'          => 'required|string|max:255',
                'phone_number'  => 'required|string|max:25|unique:users',
                'email'         => 'required|string|email|max:255|unique:users',
                'user_name'     => 'required|string|max:255|unique:users',
                'password'      => 'required|string|min:8|confirmed',
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            // Generate custom integer ID
            $customId = time() + rand(1000, 9999);

            // Create the user
            $user = new User();
            $user->id = $customId; // Assign custom ID
            $user->name = $request->name;
            $user->phone_number = $request->phone_number;
            $user->email = $request->email;
            $user->user_name = $request->user_name;
            $user->password = Hash::make($request->password);
            $user->save();

            // Generate user token
            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'message' => 'User registered successfully!!.',
                'user' => $user,
                'token' => $token,
            ], 201);
        } catch (Exception $e) {
            // Handle exceptions
            return response()->json([
                'error' => 'An error occurred during registration.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    //User Login
    public function login(Request $request)
    {
        try {
            // Validate the login request
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            // Attempt to authenticate the user
            if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return response()->json([
                    'message' => 'Invalid credentials'
                ], 401);
            }

            // Retrieve the authenticated user
            $user = Auth::user();

            // Generate a new token for the user
            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'message' => 'Logged in successfully.',
                'user' => $user,
                'token' => $token,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred during login.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function user(Request $request)
    {
        return $request->user();
    }
}
