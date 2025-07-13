<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Tentang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $banner = DB::table('banner')->select('path', 'title1', 'title2', 'description')->first();
        $tentang = DB::table('tentang')->first();
        $count = DB::table('count')->get();
        $testimoni = DB::table('testimoni')->get();

        $data = [
            'banner' => $banner,
            'tentang' => $tentang,
            'count' => $count,
            'testimoni' => $testimoni
        ];
        return view('frontend.home', $data);
    }
}