<?php

namespace App\Http\Controllers;

use App\Models\LServiceType;
use App\Models\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ServiceController extends Controller
{
    public function insertServiceType(Request $request)
    {
        try {
            $postdata= $request->all();

            // Create Service Type
            $serviceType = new LServiceType();
            $serviceType->service_type_name = $postdata['service_type_name'];
            $serviceType->active_yn = $postdata['active_yn'];
            $serviceType->created_by = Auth::user()->id;
            $serviceType->save();

            return response()->json([
                'message' => 'Service type created successfully!!.',
                'serviceType' => $serviceType,
            ], 201);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json([
                'error' => 'An error occurred during service type creation!!.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function insertService(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'service_name' => 'required|string|max:255',
                'service_types_id' => 'required|integer',
                'service_min_price' => 'nullable|numeric',
                'service_max_price' => 'nullable|numeric',
                'service_title' => 'required|string|max:255',
                'service_description' => 'nullable|string',
                'service_attachment' => 'nullable|mimes:jpeg,png,jpg,pdf,doc,docx,zip|max:5240',  // 5MB max
            ]);

            // Handle file upload if a file is attached
            if ($request->hasFile('service_attachment')) {
                $file = $request->file('service_attachment');

                // Get file details
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('service_attachments', $fileName, 'public');  // Save file to public storage
                $fileType = $file->getMimeType();  // Get the file MIME type
            }

            // Create Service
            $service = new Services();
            $service->service_name = $validatedData['service_name'];
            $service->service_types_id = $validatedData['service_types_id'];
            $service->service_min_price = $validatedData['service_min_price'] ?? '';
            $service->service_max_price = $validatedData['service_max_price'] ?? '';
            $service->service_title = $validatedData['service_title'];
            $service->service_description = $validatedData['service_description'] ?? '';
            $service->service_attachment = $filePath ?? '';
            $service->file_type = $fileType ?? '';
            $service->file_name = $fileName ?? '';
            $service->created_by = Auth::user()->id;
            $service->save();

            return response()->json([
                'message' => 'Service created successfully!',
                'service' => $service,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed.',
                'details' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred during service creation.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

}
