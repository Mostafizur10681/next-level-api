<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Exception;

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

}
