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
        Schema::create('biaya_prodi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_gelombang');
            $table->string('program_studi', 10);
            $table->decimal('biaya_pendaftaran', 15, 2)->default(0);
            $table->decimal('biaya_tri_dharma', 15, 2)->default(0);
            $table->decimal('biaya_ospek', 15, 2)->default(0);
            $table->decimal('biaya_spp', 15, 2)->default(0);
            $table->decimal('biaya_sks', 15, 2)->default(0);
            $table->tinyInteger('gratis_untuk_kip')->default(0);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id_gelombang')->references('id')->on('gelombangs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biaya_prodi');
    }
};
