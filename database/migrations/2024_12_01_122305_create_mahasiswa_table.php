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
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->string('no_pendaftaran')->nullable();
            $table->string('nama')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('jenis_kelamin', 2)->nullable();
            $table->string('nik')->unique()->nullable();
            $table->string('agama')->nullable();
            $table->string('status_kawin')->nullable();
            $table->string('kewarganegaraan')->nullable();
            $table->integer('id_ortu')->nullable();
            $table->integer('id_program_studi')->nullable();
            $table->string('asal_sekolah')->nullable();
            $table->string('pts_transfer')->nullable();
            $table->string('alamat')->nullable();
            $table->bigInteger('id_provinsi')->nullable();
            $table->bigInteger('id_kabupaten')->nullable();
            $table->bigInteger('id_kecamatan')->nullable();
            $table->bigInteger('id_desa')->nullable();
            $table->integer('kode_pos')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('pekerjaan')->nullable()->nullable();
            $table->integer('id_pembayaran')->nullable();
            $table->integer('id_dokumen')->nullable();
            $table->tinyInteger('status_pembayaran')->default(0);
            $table->tinyInteger('status_step')->nullable();
            $table->string('status_daftar')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
