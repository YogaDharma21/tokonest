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

Route::get('/testing', function () {
    $order = \App\Models\Order\Order::orderBy('id', 'desc')->first();
    return new NewOrderToSeller($order);
});

Route::post('/midtrans/callback', [MidtransController::class, 'callback']);
