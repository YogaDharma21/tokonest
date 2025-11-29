<?php

use App\Http\Controllers\MidtransController;
use App\Mail\NewOrderToSeller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'name' => config('app.name'),
        'version' => app()->version(),
        'env' => config('app.env'),
    ]);
});

Route::post('/midtrans/callback', [MidtransController::class, 'callback']);
