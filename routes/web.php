<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\InventoryController;


Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'warehouse' 
            ? redirect()->route('inventory.index') 
            : redirect()->route('services.index');
    }
    return redirect()->route('login');
});

// Rute Guest (Hanya bisa diakses jika belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});


// Group Route yang memerlukan Autentikasi
Route::middleware(['auth'])->group(function () {
    
    // Rute Logout (Bisa diakses semua role yang sudah login)
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Dashboard Utama Transaksi (Bisa diakses multi-role sesuai porsinya)
    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    

    // Khusus Merchant (Kasir) & Guild Master (Owner) - Registrasi & Pembayaran
    Route::middleware(['can:access-cashier'])->group(function () {
        Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
        Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
        Route::post('/services/{service}/pay', [ServiceController::class, 'pay'])->name('services.pay');
        Route::get('/services/{service}/invoice', [ServiceController::class, 'invoice'])->name('services.invoice');
    });

    Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');

    // Khusus Combatant (Mekanik) - Update Progress & Ambil Loot Sparepart
    Route::middleware(['can:access-mechanic'])->group(function () {
        Route::post('/services/{service}/start', [ServiceController::class, 'startProcessing'])->name('services.start');
        Route::post('/services/{service}/add-action', [ServiceController::class, 'addAction'])->name('services.add-action');
        Route::post('/services/{service}/complete', [ServiceController::class, 'completeService'])->name('services.complete');
        Route::put('/services/{service}/update-stnk', [ServiceController::class, 'updateStnk'])
        ->name('services.update-stnk');
    });

    // Khusus Admin Gudang (Inventory Manager) & Guild Master (Owner)
    Route::middleware(['can:access-warehouse'])->group(function () {
        Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
        Route::post('/inventory/{item}/restock', [InventoryController::class, 'restock'])->name('inventory.restock');
    });
});