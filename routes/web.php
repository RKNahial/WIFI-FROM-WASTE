<?php

use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\WifiFromWasteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/WifiFromWaste', function () {
    return redirect()->route('WifiFromWaste');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Device routes
    Route::resource('devices', DeviceController::class);

    // Vendor routes
    Route::resource('vendors', VendorController::class);
    Route::post('vendors/generate-voucher', [VendorController::class, 'generateVoucher'])
        ->name('vendors.generate-voucher');

    Route::get('/WifiFromWaste', [WifiFromWasteController::class, 'index'])->name('WifiFromWaste');

    Route::post('/notifications/{id}/mark-as-read', [WifiFromWasteController::class, 'markNotificationAsRead'])
        ->name('notifications.mark-as-read');

    Route::get('/generate-report', [App\Http\Controllers\ReportController::class, 'generateReport'])
        ->name('generate.report');
});

require __DIR__.'/auth.php';

// Auth::routes();

