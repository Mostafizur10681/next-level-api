<?php

namespace App\Http\Controllers;

use App\Models\ContractUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ContractUsController extends Controller
{
    public function insertContract(Request $request)
    {
        try {
            $postData = $request->all();
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email'
            ]);

            // Create Contract
            $contarct = new ContractUs();
            $contarct->name = $validatedData['name'];
            $contarct->email = $validatedData['email'];
            $contarct->service_id = $postData['service_id'];
            $contarct->message = $postData['message'];
            $contarct->created_by = Auth::user()->id??'';
            $contarct->save();

            return response()->json([
                'message' => 'Your Contract information send into to admin successfully. Please stay with us!',
                'contarct' => $contarct,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed.',
                'details' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred during contract submit.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function getContracts()
    {
        $contracts = ContractUs::get();

        if ($contracts) {
            return response()->json([
                'contracts' => $contracts
            ], 200);
        } else {
            return response()->json([
                'message' => 'Blogs not found'
            ], 404);
        }
    }

    public function getContract($contractId)
    {
        $contract = ContractUs::find($contractId);

        if ($contract) {
            return response()->json([
                'contract' => $contract
            ], 200);
        } else {
            return response()->json([
                'message' => 'Contract not found'
            ], 404);
        }
    }

}
