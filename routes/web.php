<?php

use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\WifiFromWasteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard/Home routes
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Other routes...
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Device routes
    Route::resource('devices', DeviceController::class);

    // Vendor routes
    Route::resource('vendors', VendorController::class);
    Route::post('vendors/generate-voucher', [VendorController::class, 'generateVoucher'])
        ->name('vendors.generate-voucher');

    Route::get('/WifiFromWaste', [WifiFromWasteController::class, 'index'])->name('WifiFromWaste');
});

require __DIR__.'/auth.php';

Auth::routes();

