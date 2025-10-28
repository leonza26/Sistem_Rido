<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\admin\AdminMainController;
use App\Http\Controllers\kasir\KasirMainController;

Route::get('/', function () {
    return view('auth.login');
});


// admin routes
Route::middleware(['auth', 'verified', 'rolemanager:admin'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::controller(AdminMainController::class)->group(function () {

            Route::get('/dashboard', 'admin')->name('admin');
            Route::get('/products', 'manageProducts')->name('admin.products');
            Route::get('/laporan', 'manageLaporan')->name('admin.laporan');
            Route::get('/manage_pengguna', 'managePengguna')->name('admin.manage_pengguna');

        });
    });
});


// Kasir routes
Route::middleware(['auth', 'verified', 'rolemanager:kasir'])->group(function () {
    Route::prefix('kasir')->group(function () {
        Route::controller(KasirMainController::class)->group(function () {
            Route::get('/dashboard', 'index')->name('kasir');
            // transaksi
            Route::get('/transaksi', 'transaksi')->name('kasir.transaksi');
            Route::get('/stok_barang', 'stok_barang')->name('kasir.stok_barang');


        });

    });
});




// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';
