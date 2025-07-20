<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BiayaProdi;
use App\Models\Gelombang;

class BiayaProdiSoreSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Ambil semua gelombang
        $gelombangs = Gelombang::all();
        
        // Struktur biaya untuk kelas sore berdasarkan gelombang
        $biayaSoreData = [
            1 => ['tri_dharma' => 3500000], // Gelombang 1
            2 => ['tri_dharma' => 4000000], // Gelombang 2  
            3 => ['tri_dharma' => 4500000], // Gelombang 3
        ];
        
        foreach ($gelombangs as $index => $gelombang) {
            // Tentukan biaya tri dharma berdasarkan urutan gelombang
            $gelombangIndex = $index + 1;
            $triDharma = $biayaSoreData[$gelombangIndex]['tri_dharma'] ?? 4500000; // Default ke gelombang 3 jika lebih
            
            // Buat biaya untuk kedua program studi (Manajemen dan Akuntansi)
            foreach (['mnj', 'akt'] as $prodi) {
                BiayaProdi::updateOrCreate(
                    [
                        'id_gelombang' => $gelombang->id,
                        'program_studi' => $prodi,
                        'jenis_kelas' => 'sore'
                    ],
                    [
                        'biaya_pendaftaran' => 100000,
                        'biaya_tri_dharma' => $triDharma,
                        'biaya_ospek' => 1150000,
                        'biaya_spp' => 2900000,
                        'biaya_sks' => 0, // Sesuaikan jika ada
                        'gratis_untuk_kip' => 0,
                    ]
                );
            }
        }
        
        // Update data biaya yang sudah ada untuk menambahkan jenis_kelas = 'pagi' jika belum ada
        BiayaProdi::whereNull('jenis_kelas')
            ->orWhere('jenis_kelas', '')
            ->update(['jenis_kelas' => 'pagi']);
    }
}