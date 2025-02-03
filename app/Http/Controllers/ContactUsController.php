<?php

namespace App\Http\Controllers;

use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ContactUsController extends Controller
{
    public function insertContact(Request $request)
    {
        try {
            $postData = $request->all();
            $validatedData = $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'required|email|max:100'
            ]);

            // Create Contact
            $contact = new ContactUs();
            $contact->name = $validatedData['name'];
            $contact->email = $validatedData['email'];
            $contact->service_id = $postData['service_id'];
            $contact->message = $postData['message'];
            $contact->created_by = Auth::user()->id??'';
            $contact->save();

            return response()->json([
                'message' => 'Thanks for contact with us. Please stay connected with us!',
                'contact' => $contact,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed.',
                'details' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred during contact submit.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function getContacts()
    {
        $contacts = ContactUs::get();

        if ($contacts) {
            return response()->json([
                'contacts' => $contacts
            ], 200);
        } else {
            return response()->json([
                'message' => 'Contacts not found'
            ], 404);
        }
    }

    public function getContact($contactId)
    {
        $contact = ContactUs::find($contactId);

        if ($contact) {
            return response()->json([
                'contract' => $contact
            ], 200);
        } else {
            return response()->json([
                'message' => 'Contact not found'
            ], 404);
        }
    }

}
