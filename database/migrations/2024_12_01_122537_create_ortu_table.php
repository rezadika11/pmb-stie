<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ortu', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ayah');
            $table->string('nama_ibu');
            $table->string('tempat_lahir_ayah');
            $table->string('tempat_lahir_ibu');
            $table->string('tanggal_lahir_ayah');
            $table->string('tanggal_lahir_ibu');
            $table->string('nik_ayah');
            $table->string('nik_ibu');
            $table->string('pendidikan_ayah');
            $table->string('pendidikan_ibu');
            $table->string('no_hp_ayah');
            $table->string('no_hp_ibu');
            $table->string('pekerjaan_ayah');
            $table->string('pekerjaan_ibu');
            $table->string('penghasilan_ayah');
            $table->string('penghasilan_ibu');
            $table->string('alamat_ortu');
            $table->bigInteger('id_provinsi');
            $table->bigInteger('id_kabupaten');
            $table->bigInteger('id_kecamatan');
            $table->bigInteger('id_desa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ortu');
    }
};
