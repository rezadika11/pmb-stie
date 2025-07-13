<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Registrasi;
use Illuminate\Http\Request;

class RegistrasiController extends Controller
{
    public function index($slug)
    {
        $data = Registrasi::where('slug', $slug)->first();
        return view('frontend.registrasi', compact('data'));
    }
}
