<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratTugasPenggantiDriverModel; // Ganti nama model di sini
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSurat = SuratTugasPenggantiDriverModel::count(); // ganti pemanggilan model
        $totalUsers = User::count();

        $suratTerbaru = SuratTugasPenggantiDriverModel::orderBy('tgl_surat_pembuatan', 'desc')
            ->take(5)
            ->get();
        $usersTerbaru = User::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard.index2', compact(
            'totalSurat',
            'totalUsers',
            'suratTerbaru',
            'usersTerbaru'
        ));
    }
}
