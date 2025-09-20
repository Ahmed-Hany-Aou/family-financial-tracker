<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// routes/web.php (for testing only)
Route::get('/test-env', function() {
    return [
        'app_name' => env('APP_NAME'),
        'app_env' => env('APP_ENV'),
        'instance_id' => env('HYPERSENDER_INSTANCE_ID'),
        'token' => env('HYPERSENDER_API_TOKEN') ? 'Present' : 'Missing',
        'phone' => env('MY_WHATSAPP_NUMBER'),
    ];
});