<?php

namespace App\Http\Controllers;

use App\Models\ChooseUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ChooseUsController extends Controller
{
    public function insertChooseUs(Request $request)
    {
        try {
            $postdata= $request->all();

            // Create Choose Us
            $choose = new ChooseUs();
            $choose->title = $postdata['title'];
            $choose->description = $postdata['description'];
            $choose->created_by = Auth::user()->id;
            $choose->active_yn = $postdata['active_yn'];
            $choose->save();

            return response()->json([
                'message' => 'Choose US created successfully!!.',
                'chooseUs' => $choose,
            ], 200);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json([
                'error' => 'An error occurred during choose us creation!!.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function getActiveChooseUs()
    {
        $chooseUs = ChooseUs::where('active_yn', 'Y')->get();

        if ($chooseUs) {
            return response()->json([
                'chooseUs' => $chooseUs
            ], 200);
        } else {
            return response()->json([
                'message' => 'Menu not found'
            ], 404);
        }
    }

    public function getActiveSigleChooseUs($chooseId)
    {
        $chooseUs = ChooseUs::find($chooseId);

        if ($chooseUs) {
            return response()->json([
                'chooseUs' => $chooseUs
            ], 200);
        } else {
            return response()->json([
                'message' => 'Menu not found'
            ], 404);
        }
    }

    public function updateChooseUs(Request $request, $chooseId)
    {
         $postdata= $request->all();
        try {
            $choose = ChooseUs::find($chooseId);

            $choose->title = $postdata['title'];
            $choose->description = $postdata['description'];
            $choose->active_yn = $postdata['active_yn'];
            $choose->updated_by = Auth::user()->id;
            $choose->save();

            return response()->json([
                'success' => true,
                'message' => 'Choose us successfully updated.',
                'data' => $choose,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred during choose us update.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

}
