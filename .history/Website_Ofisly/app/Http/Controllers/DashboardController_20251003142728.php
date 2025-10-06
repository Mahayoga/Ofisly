<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\DaftarLowongan;
use App\Models\LowonganPekerjaanModel;
use App\Models\SuratTugasPromotor;
use App\Models\SuratTugasMandiriModel;
use App\Models\SuratTugasPenggantiDriverModel;
use App\Models\suratPenempatanPramubaktiMandiri;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUser = User::count();
        $totalLowongan = LowonganPekerjaanModel::count();

        $totalSuratTugas = SuratTugasPromotor::count() + SuratTugasMandiriModel::count() + SuratTugasPenggantiDriverModel::count();

        
        $totalSuratPromotorBulanan = [];
        $totalSuratMandiriBulanan = [];
        $totalSuratPenggantiBulanan = [];
        $suratTugasBulanan = [];

        $suratBulanan = Carbon::now()->month;
        $suratTahunan = Carbon::now()->year;

        

        for ($i = 1; $i <= 12; $i++) {
            $tp = (int) SuratTugasPromotor::whereYear('tgl_surat_pembuatan', $suratTahunan)->whereMonth('tgl_surat_pembuatan', $i)->count();
            $tm = (int) SuratTugasMandiriModel::whereYear('tgl_surat_pembuatan', $suratTahunan)->whereMonth('tgl_surat_pembuatan', $i)->count();
            $tpd = (int) SuratTugasPenggantiDriverModel::whereYear('tgl_surat_pembuatan', $suratTahunan)->whereMonth('tgl_surat_pembuatan', $i)->count();

            $totalSuratPromotorBulanan[] = $tp;
            $totalSuratMandiriBulanan[]  = $tm;
            $totalSuratPenggantiBulanan[] = $tpd;

            $suratTugasBulanan[] = $tp + $tm + $tpd;
            $totalSuratTahunan 

        }



        return view('admin.dashboard.index2', compact(
            'totalUser',
            'totalLowongan',
            'totalSuratTugas',
            'totalSuratPromotorBulanan',
            'totalSuratMandiriBulanan',
            'totalSuratPenggantiBulanan',
            'suratTugasBulanan'
        ));
    }
}
