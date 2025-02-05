<?php

namespace App\Http\Controllers;

use App\Models\Subcription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SubcriptionController extends Controller
{
    public function insertSubcription(Request $request)
    {
        try {
            $postData = $request->all();
            $validatedData = $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'required|email|max:100'
            ]);

            // Create Subcription
            $subcription = new Subcription();
            $subcription->name = $validatedData['name'];
            $subcription->email = $validatedData['email'];
            $subcription->created_by = Auth::user()->id??1;
            $subcription->save();

            return response()->json([
                'message' => 'Thanks for your subcriptions. Please stay connected with us!',
                'subcription' => $subcription,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed.',
                'details' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred during subcription submit.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function getSubcriptions()
    {
        $subcriptions = Subcription::get();

        if ($subcriptions) {
            return response()->json([
                'subcriptions' => $subcriptions
            ], 200);
        } else {
            return response()->json([
                'message' => 'Subcription not found'
            ], 404);
        }
    }

    public function getSubcription($subcriptionId)
    {
        $subcription = Subcription::find($subcriptionId);

        if ($subcription) {
            return response()->json([
                'subcription' => $subcription
            ], 200);
        } else {
            return response()->json([
                'message' => 'Subcription not found'
            ], 404);
        }
    }

}
