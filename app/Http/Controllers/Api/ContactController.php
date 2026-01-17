<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormSubmitted;
use App\Mail\ContactAutoReply;

class ContactController extends Controller
{
    /**
     * Store a new contact form submission.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Save to database
        $contact = Contact::create($validated);

        // Send email to admin
        try {
            Mail::to(env('MAIL_FROM_ADDRESS', 'info@nptf.gov.ng'))
                ->send(new ContactFormSubmitted($contact));

            // Send auto-reply to user
            Mail::to($contact->email)
                ->send(new ContactAutoReply($contact));
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('Email sending failed: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Thank you for contacting us! We will get back to you soon.',
            'data' => [
                'id' => $contact->id,
                'name' => $contact->name,
            ]
        ], 201);
    }

    /**
     * Display all contact submissions (Admin only).
     */
    public function index(Request $request)
    {
        $query = Contact::query();

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $contacts = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 20));

        return response()->json($contacts);
    }

    /**
     * Update contact status (Admin only).
     */
    public function update(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,read,resolved',
        ]);

        $contact->update($validated);

        return response()->json([
            'message' => 'Contact status updated successfully',
            'data' => $contact
        ]);
    }
}
