<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::post('/webhook', [\App\Http\Controllers\WebhookController::class, 'handle']);

Route::post('/webhook/stripe', [\App\Http\Controllers\StripeWebhookController::class, 'handle']);


