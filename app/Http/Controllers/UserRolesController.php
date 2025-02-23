<?php


namespace App\Http\Controllers;
use App\Models\UserRoles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserRolesController extends Controller
{
    //Create User wise role
    public function insertUserRole(Request $request)
    {
        try {
            $postData = $request->all();
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
            ]);
            
            if (is_string($postData['roles'])) {
                $postData['roles'] = json_decode($postData['roles'], true);
            }
            if (!is_array($postData['roles'])) {
                return response()->json([
                    'error' => 'Invalid roles format. It must be an array.',
                ], 400);
            }
            foreach ($postData['roles'] as $role) {
                UserRoles::create([
                    'user_id' => $validated['user_id'],
                    'role_id' => $role,
                    'insert_by' => Auth::id(),
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

    public function getUserRoles($userId)
    {
        $userRoles = UserRoles::where('user_id', $userId)->get();

        if ($userRoles->isNotEmpty()) {
            return response()->json([
                'userRoles' => $userRoles
            ], 200);
        } else {
            return response()->json([
                'message' => 'User roles not found'
            ], 404);
        }
    }

    public function updateUserRoles(Request $request)
    {
        try {
            $postData = $request->all();
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
            ]);

            if (is_string($postData['roles'])) {
                $postData['roles'] = json_decode($postData['roles'], true);
            }

            if (!is_array($postData['roles'])) {
                return response()->json([
                    'error' => 'Invalid roles format. It must be an array.',
                ], 400);
            }

            UserRoles::where('user_id', $validated['user_id'])->delete();

            foreach ($postData['roles'] as $role) {
                UserRoles::create([
                    'user_id' => $validated['user_id'],
                    'role_id' => $role,
                    'insert_by' => Auth::id(),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'User roles successfully updated.',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed.',
                'details' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred during role update.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
