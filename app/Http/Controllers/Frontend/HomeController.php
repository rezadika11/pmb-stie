<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $banner = DB::table('banner')->select('path', 'title1', 'title2', 'description')->first();
        $data = [
            'banner' => $banner
        ];
        return view('frontend.home', $data);
    }
}
