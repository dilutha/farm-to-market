<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inquiry;
use Illuminate\Support\Facades\Mail;

class InquiryController extends Controller
{
    // Show contact form
    public function showForm()
    {
        return view('contact');
    }

    // Handle form submission
    public function submitForm(Request $request)
    {
        // Validate request
        $data = $request->validate([
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        // Set user_id if logged in, otherwise null
        $data['user_id'] = auth()->id(); 
        $data['status'] = 'Open';

        // Create inquiry
        Inquiry::create($data);

        // Send email to admin
        Mail::raw(
            "New inquiry received:\n\nSubject: {$data['subject']}\nMessage: {$data['message']}",
            function ($message) {
                $message->to('diluthaweerasingha@gmail.com')
                        ->subject('New Contact Inquiry');
            }
        );

        return redirect()->back()->with('success', 'Your inquiry has been submitted!');
    }
}
