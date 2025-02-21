<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserRolesController extends Controller
{
    //Create User wise role
    public function insertUserRole(Request $request)
    {
        try {
            // Validate incoming data
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id', // Ensure the user exists
                'role_ids' => 'required|array|min:1', // Expecting an array of role_ids
                'role_ids.*' => 'integer|exists:roles,role_id', // Validate each role_id exists in roles table
            ]);

            // Loop through the role IDs and create the user-role associations
            foreach ($validated['role_ids'] as $role_id) {
                UserRole::create([
                    'user_id' => $validated['user_id'],
                    'role_id' => $role_id,
                    'insert_by' => Auth::user()->id,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'User roles successfully inserted.',
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed.',
                'details' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred during role assignment.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

}
