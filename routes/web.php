<?php

use App\Http\Controllers\Backend\BannerController;
use App\Http\Controllers\Backend\BrosurController;
use App\Http\Controllers\Backend\ContactController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\PendaftaranController;
use App\Http\Controllers\Backend\RegistrasiController;
use App\Http\Controllers\Frontend\BrosurController as FrontendBrosurController;
use App\Http\Controllers\Frontend\ContactController as FrontendContactController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PendaftaranController as FrontendPendaftaranController;
use App\Http\Controllers\Frontend\RegistrasiController as FrontendRegistrasiController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('admin')->group(function () {
        Route::prefix('pendaftaran')->controller(PendaftaranController::class)->name('pendaftaran.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/tambah', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/update/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
            Route::post('/upload/image', 'uploadImage')->name('image');
            Route::get('/datatable', 'datatable')->name('datatable');
        });

        Route::prefix('registrasi')->controller(RegistrasiController::class)->name('registrasi.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/tambah', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/update/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
            Route::post('/upload/image', 'uploadImage')->name('image');
            Route::get('/datatable', 'datatable')->name('datatable');
        });

        Route::prefix('brosur')->controller(BrosurController::class)->name('brosur.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::put('/{id}', 'update')->name('update');
        });

        Route::prefix('banner')->controller(BannerController::class)->name('banner.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::put('/{id}', 'update')->name('update');
        });

        Route::prefix('kontak')->controller(ContactController::class)->name('kontak.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::put('/{id}', 'update')->name('update');
        });
    });
});


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/pendaftaran/{slug}', [FrontendPendaftaranController::class, 'index'])->name('pendaftaran');
Route::get('/registrasi/{slug}', [FrontendRegistrasiController::class, 'index'])->name('registrasi');
Route::get('/brosur', [FrontendBrosurController::class, 'index'])->name('brosur');
Route::get('/kontak', [FrontendContactController::class, 'index'])->name('kontak');

require __DIR__ . '/auth.php';
