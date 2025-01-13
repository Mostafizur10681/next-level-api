<?php

namespace App\Http\Controllers;

use App\Models\FAQ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class FAQController extends Controller
{
    public function insertFAQ(Request $request)
    {
        try {
            $postdata= $request->all();

            // Create FAQ
            $faq = new FAQ();
            $faq->faq_title = $postdata['faq_title'];
            $faq->faq_description = $postdata['faq_description'];
            $faq->created_by = Auth::user()->id;
            $faq->active_yn = $postdata['active_yn'];
            $faq->save();

            return response()->json([
                'message' => 'FAQ created successfully!!.',
                'faq' => $faq,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred during choose us creation!!.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function getActiveFAQ()
    {
        $faq = FAQ::where('active_yn', 'Y')->get();

        if ($faq) {
            return response()->json([
                'faq' => $faq
            ], 200);
        } else {
            return response()->json([
                'message' => 'Menu not found'
            ], 404);
        }
    }

    public function getActiveSigleFAQ($faqId)
    {
        $faq = FAQ::find($faqId);

        if ($faq) {
            return response()->json([
                'faq' => $faq
            ], 200);
        } else {
            return response()->json([
                'message' => 'Menu not found'
            ], 404);
        }
    }

    public function updateFAQ(Request $request, $faqId)
    {
        $postdata= $request->all();
        try {
            $faq = FAQ::find($faqId);
            $faq->faq_title = $postdata['faq_title'];
            $faq->faq_description = $postdata['faq_description'];
            $faq->active_yn = $postdata['active_yn'];
            $faq->updated_by = Auth::user()->id;
            $faq->save();

            return response()->json([
                'success' => true,
                'message' => 'FAQ successfully updated!',
                'data' => $faq,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred during FAQ update!',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

}
