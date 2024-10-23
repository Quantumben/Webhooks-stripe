<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Webhook;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $endpoint_secret = config('services.stripe.webhook_secret'); // Stripe Webhook Secret
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event (e.g. charge succeeded, payment refunded, etc.)
        if ($event->type == 'charge.succeeded') {
            $paymentIntent = $event->data->object; // Contains Stripe payment details

            Log::info('Payment successful:', (array)$paymentIntent);

            // Process your business logic here (e.g. update order status, send email, etc.)
        }

        return response()->json(['status' => 'success'], 200);
    }
}
