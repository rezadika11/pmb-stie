<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    public function index($slug)
    {
        $data = Pendaftaran::where('slug', $slug)->first();
        return view('frontend.pendaftaran', compact('data'));
    }
}
