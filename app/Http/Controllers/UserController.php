<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    //Get Users
    public function users(Request $request)
    {
        $users = User::all();
        return response()->json([
            'users' => $users
        ], 200);
    }

    //Get Single User
    public function user($userId)
    {
        $user = User::find($userId);

        if ($user) {
            return response()->json([
                'user' => $user
            ], 200);
        } else {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }
    }

    //User profile update
    public function updateUserProfile(Request $request, $userId)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'sometimes|string|max:255',
                'phone_number' => 'required|string|max:25|unique:users,phone_number,' . $userId, // Exclude current user's phone_number
                'email' => 'sometimes|email|unique:users,email,' . $userId,
                'user_name' => 'required|string|max:255|unique:users,user_name,' . $userId,
                'password' => 'sometimes|nullable|min:8|confirmed',
            ]);

            // Prepare the update data
            $updateData = $validatedData;

            // Handle password hashing if present (using Hash::make)
            if (isset($validatedData['password'])) {
                $updateData['password'] = Hash::make($validatedData['password']);
            }

            // Perform the update using the `update()` method
            $updated = User::where('id', $userId)->update($updateData);

            if ($updated) {
                return response()->json([
                    'message' => 'User profile updated successfully',
                    'user' => $updateData,
                ], 200);
            } else {
                return response()->json([
                    'message' => 'User not found or no changes made',
                ], 404);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }
}
