<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiayaProdi extends Model
{
    use HasFactory;

    protected $table = 'biaya_prodi';

    protected $fillable = [
        'id_gelombang',
        'program_studi',
        'biaya_pendaftaran',
        'biaya_tri_dharma',
        'biaya_ospek',
        'biaya_spp',
        'biaya_sks',
        'gratis_untuk_kip',
    ];

    protected $casts = [
        'biaya_pendaftaran' => 'decimal:2',
        'biaya_tri_dharma' => 'decimal:2',
        'biaya_ospek' => 'decimal:2',
        'biaya_spp' => 'decimal:2',
        'biaya_sks' => 'decimal:2',
        'gratis_untuk_kip' => 'boolean',
    ];

    /**
     * Relasi ke model Gelombang
     */
    public function gelombang()
    {
        return $this->belongsTo(Gelombang::class, 'id_gelombang');
    }

    /**
     * Mendapatkan nama program studi
     */
    public function getNamaProgramStudiAttribute()
    {
        $prodi_list = ['mnj' => 'Manajemen', 'akt' => 'Akuntansi'];
        return $prodi_list[$this->program_studi] ?? $this->program_studi;
    }

    /**
     * Accessor untuk mendapatkan total biaya
     */
    public function getTotalBiayaAttribute()
    {
        return $this->biaya_pendaftaran +
            $this->biaya_tri_dharma +
            $this->biaya_ospek +
            $this->biaya_spp +
            $this->biaya_sks;
    }

    /**
     * Mendapatkan biaya berdasarkan gelombang dan program studi
     */
    public static function getBiayaByGelombangProdi($idGelombang, $programStudi)
    {
        return self::where('id_gelombang', $idGelombang)
            ->where('program_studi', $programStudi)
            ->first();
    }

    /**
     * Mendapatkan biaya untuk jenis pendaftaran tertentu
     */
    public function getBiayaForJalurMasuk($jalurMasuk)
    {
        // Jika jalur masuk KIP dan gratis untuk KIP, return 0
        if ($jalurMasuk === 'kip' && $this->gratis_untuk_kip) {
            return [
                'biaya_pendaftaran' => 0,
                'biaya_tri_dharma' => 0,
                'biaya_ospek' => 0,
                'biaya_spp' => 0,
                'biaya_sks' => 0,
                'total_biaya' => 0,
                'is_gratis_kip' => true
            ];
        }

        return [
            'biaya_pendaftaran' => $this->biaya_pendaftaran,
            'biaya_tri_dharma' => $this->biaya_tri_dharma,
            'biaya_ospek' => $this->biaya_ospek,
            'biaya_spp' => $this->biaya_spp,
            'biaya_sks' => $this->biaya_sks,
            'total_biaya' => $this->total_biaya,
            'is_gratis_kip' => false
        ];
    }

    /**
     * Mendapatkan detail biaya dalam format yang mudah dibaca
     */
    public function getDetailBiaya()
    {
        return [
            'Biaya Pendaftaran' => 'Rp ' . number_format($this->biaya_pendaftaran, 0, ',', '.'),
            'Biaya Tri Dharma' => 'Rp ' . number_format($this->biaya_tri_dharma, 0, ',', '.'),
            'Biaya Ospek' => 'Rp ' . number_format($this->biaya_ospek, 0, ',', '.'),
            'Biaya SPP' => 'Rp ' . number_format($this->biaya_spp, 0, ',', '.'),
            'Biaya SKS' => 'Rp ' . number_format($this->biaya_sks, 0, ',', '.'),
            'Total Biaya' => 'Rp ' . number_format($this->total_biaya, 0, ',', '.')
        ];
    }
}
