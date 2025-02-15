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
            ], 200);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json([
                'error' => 'An error occurred during service type creation!!.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function getServiceTypes()
    {
        $serviceTypes = LServiceType::all();

        if ($serviceTypes) {
            return response()->json([
                'serviceTypes' => $serviceTypes
            ], 200);
        } else {
            return response()->json([
                'message' => 'Service Types not found'
            ], 404);
        }
    }

    public function getServiceType($serviceTypeId)
    {
        $serviceType = LServiceType::find($serviceTypeId);

        if ($serviceType) {
            return response()->json([
                'serviceType' => $serviceType
            ], 200);
        } else {
            return response()->json([
                'message' => 'Service Types not found'
            ], 404);
        }
    }

    public function updateServiceType(Request $request, $chooseId)
    {
        $postdata= $request->all();
        try {
            $serviceType = LServiceType::find($chooseId);
            $serviceType->service_type_name = $postdata['service_type_name'];
            $serviceType->active_yn = $postdata['active_yn'];
            $serviceType->updated_by = Auth::user()->id;
            $serviceType->save();

            return response()->json([
                'success' => true,
                'message' => 'Service type successfully updated!',
                'data' => $serviceType,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred during choose us update.',
                'details' => $e->getMessage(),
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
                'service_description' => 'nullable|string'
            ]);

            // Process base64 image (if provided)
            $serviceAttachment = $this->saveBase64Image($request->service_attachment);

            // Create Service
            $service = new Services();
            $service->service_name = $validatedData['service_name'];
            $service->service_types_id = $validatedData['service_types_id'];
            $service->service_min_price = $validatedData['service_min_price'] ?? '';
            $service->service_max_price = $validatedData['service_max_price'] ?? '';
            $service->service_title = $validatedData['service_title'];
            $service->service_description = $validatedData['service_description'] ?? '';
            $service->service_attachment = $serviceAttachment ?? ''; // Store base64 image
            $service->created_by = Auth::user()->id;
            $service->active_yn = $request->input('active_yn');
            $service->save();

            return response()->json([
                'message' => 'Service created successfully!',
                'service' => $service,
            ], 200);
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


    public function getActiveServices()
    {
        $services = Services::all();

        if ($services) {
            return response()->json([
                'services' => $services
            ], 200);
        } else {
            return response()->json([
                'message' => 'Services not found'
            ], 404);
        }
    }

    public function getActiveService($serviceId)
    {
        $service = Services::find($serviceId);

        if ($service) {
            return response()->json([
                'service' => $service
            ], 200);
        } else {
            return response()->json([
                'message' => 'Service not found'
            ], 404);
        }
    }

    public function updateService(Request $request, $id)
    {
        try {
            // Validate input data
            $validatedData = $request->validate([
                'service_name' => 'required|string|max:255',
                'service_types_id' => 'required|integer',
                'service_min_price' => 'nullable|numeric',
                'service_max_price' => 'nullable|numeric',
                'service_title' => 'required|string|max:255',
                'service_description' => 'nullable|string',
                'service_attachment' => 'nullable|string', // Base64 string
            ]);

            // Find the service
            $service = Services::find($id);

            if (!$service) {
                return response()->json([
                    'error' => 'Service not found.',
                ], 404);
            }

            // Handle base64 image (if provided)
            if ($request->has('service_attachment')) {
                $serviceAttachment = $this->saveBase64Image($request->service_attachment);
                $service->service_attachment = $serviceAttachment;
            }

            // Update other service attributes
            $service->service_name = $validatedData['service_name'];
            $service->service_types_id = $validatedData['service_types_id'];
            $service->service_min_price = $validatedData['service_min_price'] ?? '';
            $service->service_max_price = $validatedData['service_max_price'] ?? '';
            $service->service_title = $validatedData['service_title'];
            $service->service_description = $validatedData['service_description'] ?? '';
            $service->updated_by = Auth::user()->id;
            $service->active_yn = $request->input('active_yn');
            $service->save();

            return response()->json([
                'message' => 'Service updated successfully!',
                'service' => $service,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed.',
                'details' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred during service update.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }


    private function saveBase64Image($base64String)
    {
        if (!$base64String) {
            return null; // No image provided
        }

        // Ensure it's a valid base64 string
        if (preg_match('/^data:image\/(\w+);base64,/', $base64String, $matches)) {
            return $base64String; // Return base64 directly
        }

        return null; // Invalid format
    }


}
