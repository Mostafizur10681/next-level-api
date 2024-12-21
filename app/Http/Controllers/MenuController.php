<?php


namespace App\Http\Controllers;

use App\Models\Menus;
use App\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class MenuController extends Controller
{
    //Create Menu
    public function insertMenu(Request $request)
    {
        try {
            $validated = $request->validate([
                'menu_name' => 'required|string|max:255',
                'menu_order_no' => 'required|integer',
                'base_url' => 'required|url',
                'menu_icon' => 'nullable|string|max:255',
                'active_yn' => 'required|string|max:1'
            ]);

            $menu = Menus::create([
                'menu_name' => $validated['menu_name'],
                'menu_order_no' => $validated['menu_order_no'],
                'base_url' => $validated['base_url'],
                'menu_icon' => $validated['menu_icon'],
                'active_yn' => $validated['active_yn'],
                'insert_by' => Auth::user()->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Menu successfully inserted!!.',
                'data' => $menu,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed.',
                'details' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred during menu creation.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    //get menus
    public function getActiveMenus()
    {
        $menus = Menus::where('active_yn', 'Y')->get();

        if ($menus) {
            return response()->json([
                'menus' => $menus
            ], 200);
        } else {
            return response()->json([
                'message' => 'Menu not found'
            ], 404);
        }
    }

    public function getActiveMenu($menuId)
    {
        $menu = Menus::find($menuId);

        if ($menu) {
            return response()->json([
                'menu' => $menu
            ], 200);
        } else {
            return response()->json([
                'message' => 'Menu not found'
            ], 404);
        }
    }

    public function updateMenu(Request $request, $id)
    {
        try {
            // Validate input data
            $validated = $request->validate([
                'menu_name' => 'required|string|max:255',
                'menu_order_no' => 'required|integer',
                'base_url' => 'required|url',
                'menu_icon' => 'nullable|string|max:255',
                'active_yn' => 'required|string|max:1',
            ]);

            // Find the menu by id
            $menu = Menus::find($id);

            // Check if the menu exists
            if (!$menu) {
                return response()->json([
                    'error' => 'Menu not found.',
                ], 404);
            }

            // Update menu attributes
            $menu->menu_name = $validated['menu_name'];
            $menu->menu_order_no = $validated['menu_order_no'];
            $menu->base_url = $validated['base_url'];
            $menu->menu_icon = $validated['menu_icon'] ?? $menu->menu_icon; // Preserve previous icon if not provided
            $menu->active_yn = $validated['active_yn'];
            $menu->update_by = Auth::user()->id; // Update the user who made the changes

            // Save the changes
            $menu->save();

            return response()->json([
                'success' => true,
                'message' => 'Menu successfully updated.',
                'data' => $menu,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed.',
                'details' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred during menu update.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    //create role
    public function insertRole(Request $request)
    {
        try {
            // Validate the incoming request
            $validated = $request->validate([
                'role_name' => 'required|string|max:255',
                'role_key' => 'required|string|max:50|unique:roles,role_key',
                'grant_all_yn' => 'required|string|max:1|in:Y,N',
                'active_yn' => 'required|string|max:1|in:Y,N',
            ]);

            // Create a new role
            $role = Roles::create([
                'role_name' => $validated['role_name'],
                'role_key' => $validated['role_key'],
                'grant_all_yn' => $validated['grant_all_yn'],
                'active_yn' => $validated['active_yn'],
                'insert_by' => Auth::user()->id,
            ]);

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'Role successfully inserted!',
                'data' => $role,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed.',
                'details' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred during role creation.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function getRoles()
    {
        $roles = Roles::all();

        if ($roles) {
            return response()->json([
                'menus' => $roles
            ], 200);
        } else {
            return response()->json([
                'message' => 'Menu not found'
            ], 404);
        }
    }

    public function getRole($roleId)
    {
        $role = Roles::find($roleId);

        if ($role) {
            return response()->json([
                'role' => $role
            ], 200);
        } else {
            return response()->json([
                'message' => 'Menu not found'
            ], 404);
        }
    }

}
