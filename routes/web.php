<?php

use App\Http\Controllers\admin\AdminMainController;
use App\Http\Controllers\kasir\KasirMainController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});


// Area pengelolaan toko (prefix /admin) dipakai oleh Pemilik dan Admin.
// Pemilik: semua menu. Admin: hanya menu Produk dan Pengguna.
Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::controller(AdminMainController::class)->group(function () {

            // menu khusus Pemilik: Dashboard, Laporan, Modal Kasir
            Route::middleware('rolemanager:pemilik')->group(function () {
                Route::get('/dashboard', 'admin')->name('admin');

                Route::get('/laporan', 'manageLaporan')->name('admin.laporan');

                // Export routes
                Route::get('/laporan/pdf', 'exportPDF')->name('admin.laporan.pdf');
                Route::get('/laporan/excel', 'exportExcel')->name('admin.laporan.excel');

                // modal kasir
                Route::get('/modal-kasir', 'manageModalKasir')->name('admin.modal_kasir');
                Route::post('/modal-kasir', 'storeModalKasir')->name('admin.modal_kasir.store');
            });

            // menu Pemilik dan Admin: Produk, Pengguna
            Route::middleware('rolemanager:pemilik,admin')->group(function () {
                Route::get('/products', 'manageProducts')->name('admin.products');
                Route::post('/produk/store', 'storeProduk')->name('admin.produk.store');
                Route::put('/produk/{id}', 'updateProduk')->name('admin.produk.update');
                Route::delete('/produk/{id}', 'destroyProduk')->name('admin.produk.destroy');
                Route::get('/produk/download_template', 'downloadTemplateProduk')->name('admin.produk.download_template');
                Route::post('/produk/import', 'importProduk')->name('admin.produk.import');

                // user management
                Route::get('/user/manage_pengguna', 'managePengguna')->name('admin.manage_pengguna');
                Route::post('/users', 'store')->name('users.store');
                Route::put('/users/{id}', 'update')->name('users.update');
                Route::delete('/users/{id}', 'destroy')->name('users.destroy');
            });
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
            Route::get('/transaksi/finish', 'finishTransaksi')->name('kasir.transaksi.finish');
            Route::post('/transaksi/checkout', 'checkout')->name('kasir.transaksi.checkout');
            Route::get('/stok_barang', 'stok_barang')->name('kasir.stok_barang');
            Route::post('/simpan-transaksi', 'simpanTransaksi')->name('kasir.simpanTransaksi');
        });
    });
});




// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__ . '/auth.php';
