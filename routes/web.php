<?php

use App\Http\Controllers\Backend\Admin\LaporanController;
use App\Http\Controllers\Backend\Admin\PmbController;
use App\Http\Controllers\Backend\BannerController;
use App\Http\Controllers\Backend\BiayaProdiController;
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
use App\Http\Controllers\Backend\Admin\ProfilController as AdminProfilController;
use App\Http\Controllers\Backend\Admin\TahunAkademikController as AdminTahunAkademikController;
use App\Http\Controllers\Backend\CountController;
use App\Http\Controllers\Backend\GelombangController;
use App\Http\Controllers\Backend\TahunAkademikController;
use App\Http\Controllers\Backend\TentangController;
use App\Http\Controllers\Backend\TestimoniController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// Route::get('/storage-link', function() {
//     try {
//         \Artisan::call('storage:link');
//         return "Storage link created successfully";
//     } catch (\Exception $e) {
//         return "Error: " . $e->getMessage();
//     }
// });

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

    Route::prefix('tentang')->controller(TentangController::class)->name('tentang.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::put('/{id}', 'update')->name('update');
    });

    Route::prefix('count')->controller(CountController::class)->name('count.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/tambah', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::get('/datatable', 'datatable')->name('datatable');
    });

    Route::prefix('testimoni')->controller(TestimoniController::class)->name('testimoni.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/tambah', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::get('/datatable', 'datatable')->name('datatable');
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

    Route::prefix('tahun-akademik')->controller(TahunAkademikController::class)->name('tahun_akademik.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/tambah', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::get('/datatable', 'datatable')->name('datatable');

        Route::post('/set-aktif/{id}', 'setAktif')->name('setAktif');
    });

    Route::prefix('gelombang')->controller(GelombangController::class)->name('gelombang.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::get('/datatable', 'datatable')->name('datatable');
        Route::post('/set-aktif/{id}', 'setAktif')->name('setAktif');
    });

    Route::prefix('biaya-prodi')->controller(BiayaProdiController::class)->name('biaya_prodi.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/datatable', 'getData')->name('datatable');
        Route::get('/tambah', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    Route::get('/profil', [SuperadminProfilController::class, 'edit'])->name('superadmin.profile');
    Route::post('/update-profil', [SuperadminProfilController::class, 'update'])->name('superadmin.updateProfile');
});

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/profil', [AdminProfilController::class, 'edit'])->name('admin.profile');
    Route::post('/update-profil', [AdminProfilController::class, 'update'])->name('admin.updateProfile');
    Route::prefix('pmb')->controller(PmbController::class)->name('pmb.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/tambah', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/detail-pmb/{id}', 'detail')->name('detail');
        Route::get('/datatable', 'datatable')->name('datatable');

        Route::get('/dokumen/pas-foto/{id}', 'showFoto')->name('showFoto');
        Route::get('/dokumen/ktp/{id}', 'showKTP')->name('showKTP');
        Route::get('/dokumen/kk/{id}', 'showKK')->name('showKK');
        Route::get('/dokumen/daftar-nilai/{id}', 'showDaftarNilai')->name('showDaftarNilai');
        Route::get('/dokumen/kartu-bantuan/{id}', 'showKIP')->name('showKIP');
        Route::get('/dokumen/ijazah/{id}', 'showIjazah')->name('showIjazah');
        Route::get('/dokumen/bukti-pembayaran/{id}', 'showPembayaran')->name('showPembayaran');

        Route::post('/konfirmasi-pendaftaran', 'konfirmasiDaftar')->name('konfirmasiDaftar');
        Route::post('/tolak-pendaftaran', 'tolakPendaftaran')->name('tolakPendaftaran');
    });
    // Route::prefix('tahun-akademik')->controller(AdminTahunAkademikController::class)->name('admin.tahun_akademik.')->group(function () {
    //     Route::get('/', 'index')->name('index');
    //     Route::get('/tambah', 'create')->name('create');
    //     Route::post('/store', 'store')->name('store');
    //     Route::get('/edit/{id}', 'edit')->name('edit');
    //     Route::post('/update/{id}', 'update')->name('update');
    //     Route::delete('/{id}', 'destroy')->name('destroy');
    //     Route::get('/datatable', 'datatable')->name('datatable');

    //     Route::post('/set-aktif/{id}', 'setAktif')->name('setAktif');
    // });
    Route::prefix('laporan')->controller(LaporanController::class)->name('laporan.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/datatable', 'datatable')->name('datatable');
        Route::get('/export-mahasiswa', 'exportExcel')->name('export');
    });
});

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
Route::get('/download-brosur', [FrontendBrosurController::class, 'downloadBrosur'])->name('downloadBrosur');
Route::get('/kontak', [FrontendContactController::class, 'index'])->name('kontak');

require __DIR__ . '/auth.php';
