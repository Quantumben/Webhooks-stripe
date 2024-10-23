<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebhookController extends Controller
{
    // public function handle(Request $request)
    // {
    //     // Validate the request, depending on your webhook source
    //     $data = $request->all(); // Capture the data sent to the webhook

    //     // You can log or process the data here
    //     \Log::info('Webhook received:', $data);

    //     // Respond with a 200 status code (OK) to acknowledge the webhook receipt
    //     return response()->json(['status' => 'success'], 200);
    // }

    public function handle(Request $request)
    {
        $signature = $request->header('X-Signature'); // Replace with the actual header used by your webhook provider
        $computedSignature = hash_hmac('sha256', $request->getContent(), config('services.webhook_secret'));

        if (!hash_equals($signature, $computedSignature)) {
            // If the signatures do not match, return a 400 Bad Request
            return response()->json(['status' => 'Unauthorized'], 401);
        }

        // Handle the webhook event here...

        return response()->json(['status' => 'success'], 200);
    }
}
