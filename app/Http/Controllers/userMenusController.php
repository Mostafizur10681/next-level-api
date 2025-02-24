<?php


namespace App\Http\Controllers;
use App\Models\userMenus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class userMenusController extends Controller
{
    public function insertUserMenu(Request $request)
    {
        try {
            $postData = $request->all();
            // Validate the incoming request
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'menu_id' => 'required|integer|exists:menus,menu_id',
                'submenus' => 'required', // Ensure submenus is present
            ]);

            // Decode submenus if it's a JSON string
            if (is_string($postData['submenus'])) {
                $postData['submenus'] = json_decode($postData['submenus'], true);
            }

            // Validate that submenus is an array
            if (!is_array($postData['submenus'])) {
                return response()->json([
                    'error' => 'Invalid submenus format. It must be an array.',
                ], 400);
            }

            // Insert the menu with submenus stored as JSON
            $userMenu = UserMenus::create([
                'user_id' => $validated['user_id'],
                'menu_id' => $validated['menu_id'],
                'submenus' => json_encode($postData['submenus']), // Store submenus as JSON
                'insert_by' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User menu successfully inserted.',
                'data' => $userMenu,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed.',
                'details' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred during menu assignment.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
