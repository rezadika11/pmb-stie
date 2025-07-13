<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brosur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrosurController extends Controller
{
    public function index()
    {
        $data = Brosur::first();
        return view('frontend.brosur', compact('data'));
    }

    public function downloadBrosur()
    {
        $brosur = Brosur::first();

        // Coba dengan disk berbeda
        if (!Storage::disk('local')->exists($brosur->path)) {
            // Coba public disk
            if (!Storage::disk('public')->exists($brosur->path)) {
                \Log::error('File not found: ' . $brosur->path);
                abort(404, 'File tidak ditemukan');
            }

            // Jika ada di public disk
            return Storage::disk('public')->download($brosur->path, $brosur->image);
        }

        // Download dari local disk
        return Storage::download($brosur->path, $brosur->image);
    }
}
