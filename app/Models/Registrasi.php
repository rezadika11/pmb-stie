<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Registrasi extends Model
{
    protected $fillable = ['name', 'slug', 'content'];
    protected $table = 'registrasi';

    protected static function boot()
    {
        parent::boot();

        // Observer untuk menghapus gambar yang tidak terpakai saat insert
        static::created(function ($model) {
            self::cleanupUnusedImages($model);
        });

        // Observer untuk menghapus gambar yang tidak terpakai saat update
        static::updated(function ($model) {
            self::cleanupUnusedImages($model);
        });

        // Observer untuk menghapus gambar saat model dihapus
        static::deleting(function ($model) {
            // Ekstrak semua gambar dari konten
            preg_match_all('/<img[^>]+src="([^">]+)"/', $model->content, $matches);

            $images = $matches[1] ?? [];

            foreach ($images as $imageUrl) {
                // Debug log
                \Log::info('Deleting image on model delete: ' . $imageUrl);

                // Ekstrak nama file dari URL
                $filename = basename(parse_url($imageUrl, PHP_URL_PATH));

                // Coba beberapa metode penghapusan
                $paths = [
                    'registrasi/' . $filename,
                    $filename
                ];

                foreach ($paths as $relativePath) {
                    if (Storage::disk('public')->exists($relativePath)) {
                        Storage::disk('public')->delete($relativePath);
                        \Log::info('Deleted image: ' . $relativePath);
                    }
                }
            }
        });
    }

    /**
     * Metode untuk membersihkan gambar yang tidak terpakai
     */
    protected static function cleanupUnusedImages($model)
    {
        // Ambil semua gambar yang ada di storage pendaftaran pada saat insert/update
        $storageImages = Storage::disk('public')->files('registrasi');

        // Ekstrak semua gambar dari konten saat ini
        preg_match_all('/<img[^>]+src="([^">]+)"/', $model->content, $matches);
        $usedImages = $matches[1] ?? [];

        // Filter nama file yang digunakan
        $usedFilenames = collect($usedImages)
            ->map(function ($imageUrl) {
                return basename(parse_url($imageUrl, PHP_URL_PATH));
            })
            ->toArray();

        // Cek dan hapus gambar yang tidak digunakan
        foreach ($storageImages as $storedImage) {
            $filename = basename($storedImage);

            // Periksa apakah gambar ada di konten
            if (!in_array($filename, $usedFilenames)) {
                // Hapus gambar yang tidak digunakan
                Storage::disk('public')->delete($storedImage);

                // Log penghapusan
                \Log::info('Deleted unused image on insert/update: ' . $storedImage);
            }
        }
    }

    /**
     * Metode untuk membersihkan gambar yang tidak terpakai di seluruh model
     */
    public static function cleanupAllUnusedImages()
    {
        // Ambil semua konten dari model
        $allContents = self::pluck('content');

        // Kumpulkan semua gambar yang digunakan
        $usedImages = [];
        foreach ($allContents as $content) {
            preg_match_all('/<img[^>]+src="([^">]+)"/', $content, $matches);
            $usedImages = array_merge($usedImages, $matches[1] ?? []);
        }

        // Filter nama file yang digunakan
        $usedFilenames = collect($usedImages)
            ->map(function ($imageUrl) {
                return basename(parse_url($imageUrl, PHP_URL_PATH));
            })
            ->unique()
            ->toArray();

        // Ambil semua gambar di storage
        $storageImages = Storage::disk('public')->files('registrasi');

        // Hapus gambar yang tidak digunakan
        foreach ($storageImages as $storedImage) {
            $filename = basename($storedImage);

            if (!in_array($filename, $usedFilenames)) {
                Storage::disk('public')->delete($storedImage);

                \Log::info('Deleted globally unused image: ' . $storedImage);
            }
        }
    }
}
