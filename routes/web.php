<?php

use App\Http\Controllers\Backend\BannerController;
use App\Http\Controllers\Backend\BrosurController;
use App\Http\Controllers\Backend\ContactController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\FormulirController;
use App\Http\Controllers\Backend\Mhs\ProfilController;
use App\Http\Controllers\Backend\PendaftaranController;
use App\Http\Controllers\Backend\RegistrasiController;
use App\Http\Controllers\Backend\UsersController;
use App\Http\Controllers\Frontend\BrosurController as FrontendBrosurController;
use App\Http\Controllers\Frontend\ContactController as FrontendContactController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PendaftaranController as FrontendPendaftaranController;
use App\Http\Controllers\Frontend\RegistrasiController as FrontendRegistrasiController;
use App\Http\Controllers\Backend\ProfilController as SuperadminProfilController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::prefix('superadmin')->middleware(['auth', 'role:superadmin'])->group(function () {
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

    Route::prefix('users')->controller(UsersController::class)->name('users.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/tambah', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::get('/datatable', 'datatable')->name('datatable');
    });

    Route::get('/profil', [SuperadminProfilController::class, 'edit'])->name('superadmin.profile');
    Route::post('/update-profil', [SuperadminProfilController::class, 'update'])->name('superadmin.updateProfile');
});

// Route::middleware(['auth', 'role:admin'])->group(function () {
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// });

Route::prefix('mhs')->middleware(['auth', 'role:mhs'])->group(function () {
    Route::get('/profil', [ProfilController::class, 'edit'])->name('profile');
    Route::post('/update-profil', [ProfilController::class, 'update'])->name('update.profile');
    Route::prefix('formulir')->controller(FormulirController::class)->name('formulir.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/get-kabupaten', 'getKabupaten')->name('getKabupaten');
        Route::post('/get-kecamatan', 'getKecamatan')->name('getKecamatan');
        Route::post('/get-desa', 'getDesa')->name('getDesa');
        Route::post('/check-nik-uniquenessstep1', [FormulirController::class, 'checkNikUniquenessStep1'])->name('check.nik.checkNikUniquenessStep1');
        Route::post('/simpan-step1', 'simpanStep1')->name('simpan.step1');
        //Wilayah Ortu
        Route::post('/get-kabupaten-ortu', 'getKabupatenOrtu')->name('getKabupatenOrtu');
        Route::post('/get-kecamatan-ortu', 'getKecamatanOrtu')->name('getKecamatanOrtu');
        Route::post('/get-desa-ortu', 'getDesaOrtu')->name('getDesaOrtu');
        Route::post('/check-nik-ortu-uniqueness', [FormulirController::class, 'checkNikOrtuUniqueness'])
            ->name('check.nik.ortu.uniqueness');
        Route::post('/simpan-step2', 'simpanStep2')->name('simpan.step2');
        Route::post('/simpan-step3', 'simpanStep3')->name('simpan.step3');
        Route::post('/simpan-step4', 'simpanStep4')->name('simpan.step4');

        //upload pembayaran
        Route::post('/simpan-pembayaran', 'simpanBuktiPembayaran')->name('simpanBuktiPembayaran');
        Route::get('/cetak-bukti-pembayaran/{id}', 'downloadBuktiPembayaran')->name('downloadBuktiPembayaran');
    });
});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/pendaftaran/{slug}', [FrontendPendaftaranController::class, 'index'])->name('pendaftaran');
Route::get('/registrasi/{slug}', [FrontendRegistrasiController::class, 'index'])->name('registrasi');
Route::get('/brosur', [FrontendBrosurController::class, 'index'])->name('brosur');
Route::get('/kontak', [FrontendContactController::class, 'index'])->name('kontak');

require __DIR__ . '/auth.php';
