<?php


namespace App\Http\Controllers;

use App\Models\Menus;
use App\Models\Roles;
use App\Models\SubMenus;
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
    public function menus()
    {
        $menus = Menus::all();

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
            $menu->update_by = Auth::user()->id;

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

    //Create Sub menus
    public function insertSubMenu(Request $request)
    {
        try {

            $validated = $request->validate([
                'sub_menu_name' => 'required|string|max:255',
                'menu_id' => 'required|integer',
                'base_url' => 'required|url',
                'menu_icon' => 'nullable|string|max:1000',
                'menu_order_no' => 'required|integer|unique:sub_menus,menu_order_no',
                'active_yn' => 'required|string|max:1'
            ]);

            $subMenu = SubMenus::create([
                'sub_menu_name' => $validated['sub_menu_name'],
                'menu_id' => $validated['menu_id'],
                'base_url' => $validated['base_url'],
                'menu_icon' => $validated['menu_icon'],
                'menu_order_no' => $validated['menu_order_no'],
                'active_yn' => $validated['active_yn'],
                'insert_by' => Auth::user()->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sub Menu successfully inserted!!.',
                'data' => $subMenu,
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

    //get Sub menus
    public function submenus()
    {
        $subMenus = SubMenus::all();

        if ($subMenus) {
            return response()->json([
                'submenus' => $subMenus
            ], 200);
        } else {
            return response()->json([
                'message' => 'Sub Menu not found'
            ], 404);
        }
    }

     public function submenu($subMenuId)
    {
        $submenu = SubMenus::find($subMenuId);

        if ($submenu) {
            return response()->json([
                'submenu' => $submenu
            ], 200);
        } else {
            return response()->json([
                'message' => 'Sub Menu not found'
            ], 404);
        }
    }

    public function updateSubMenu(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'sub_menu_name' => 'required|string|max:255',
                'menu_id' => 'required|integer',
                'base_url' => 'required|url',
                'menu_icon' => 'nullable|string|max:1000',
                'menu_order_no' => "required|integer",
                'active_yn' => 'required|string|max:1'
            ]);

            $subMenu = SubMenus::find($id);

            if (!$subMenu) {
                return response()->json([
                    'error' => 'Sub Menu not found.',
                ], 404);
            }

            $subMenu->update([
                'sub_menu_name' => $validated['sub_menu_name'],
                'menu_id' => $validated['menu_id'],
                'base_url' => $validated['base_url'],
                'menu_icon' => $validated['menu_icon'],
                'menu_order_no' => $validated['menu_order_no'],
                'active_yn' => $validated['active_yn'],
                'update_by' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sub Menu successfully updated!',
                'data' => $subMenu,
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
                'message' => 'Roles not found'
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
                'message' => 'Role not found'
            ], 404);
        }
    }

    public function getActiveRoles()
    {
        $roles = Roles::where('active_yn', 'Y')->get();

        if ($roles) {
            return response()->json([
                'roles' => $roles
            ], 200);
        } else {
            return response()->json([
                'message' => 'Roles not found'
            ], 404);
        }
    }


    public function updateRole(Request $request, $id)
    {
        try {
            // Find the role by id
            $role = Roles::find($id);

            // Check if the role exists
            if (!$role) {
                return response()->json([
                    'error' => 'Role not found.',
                ], 404);
            }

            // Validate input data (exclude role_key from validation)
            $validated = $request->validate([
                'role_name' => 'required|string|max:255',
                'grant_all_yn' => 'required|string|max:1|in:Y,N',
                'active_yn' => 'required|string|max:1|in:Y,N',
            ]);

            // Check if role_key is being modified
            if ($request->has('role_key') && $request->role_key !== $role->role_key) {
                return response()->json([
                    'error' => 'Role key cannot be changed.',
                ], 400);
            }

            // Update role attributes
            $role->role_name = $validated['role_name'];
            $role->grant_all_yn = $validated['grant_all_yn'];
            $role->active_yn = $validated['active_yn'];
            $role->update_by = Auth::user()->id;
            $role->save();

            return response()->json([
                'success' => true,
                'message' => 'Role successfully updated.',
                'data' => $role,
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
