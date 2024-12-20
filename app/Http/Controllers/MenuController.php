<?php


namespace App\Http\Controllers;

use App\Models\Menus;
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
            ], 201);
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
}
