<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data['jmlUser'] = User::count();
        $data['jmlMhs'] = User::where('roles', 'mhs')->count();
        $data['jmlLaki'] = Mahasiswa::where('jenis_kelamin', 'L')->count();
        $data['jmlP'] = Mahasiswa::where('jenis_kelamin', 'P')->count();

        return view('backend.dashboard', $data);
    }
}
