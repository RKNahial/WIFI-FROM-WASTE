<?php

use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;
use App\Services\MikrotikService;
use Illuminate\Http\Request;

Route::apiResource('products', ProductController::class);

Route::get('/hotspot/users', function (MikrotikService $mikrotik) {
    return $mikrotik->getActiveUsers();
});

Route::post('/hotspot/voucher', function (Request $request, MikrotikService $mikrotik) {
    return $mikrotik->createVoucher(
        $request->username,
        $request->password,
        $request->profile,
        $request->validity
    );
});

Route::get('/test', function () {
    return ['message' => 'API is working!'];
});