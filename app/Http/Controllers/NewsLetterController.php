<?php

namespace App\Http\Controllers;

use App\Models\NewsLetter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class NewsLetterController extends Controller
{
    public function insertNewsLetter(Request $request)
    {
        try {
            $postData = $request->all();
            $validatedData = $request->validate([
                'email' => 'required|email|max:100'
            ]);

            $newsLetter = new NewsLetter();
            $newsLetter->email = $validatedData['email'];
            $newsLetter->created_by = Auth::user()->id??1;
            $newsLetter->save();

            return response()->json([
                'message' => 'Thanks for your attendence. Please stay connected with us!',
                'newsLetter' => $newsLetter,
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

    public function getNewsLetter()
    {
        $newsLetters = NewsLetter::all();

        if ($newsLetters) {
            return response()->json([
                'newsLetters' => $newsLetters
            ], 200);
        } else {
            return response()->json([
                'message' => 'NewsLetters not found'
            ], 404);
        }
    }
}
