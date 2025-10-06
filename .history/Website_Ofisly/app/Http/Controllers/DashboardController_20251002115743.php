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

        
        $suratTugasBulanan = [];
        for ($i = 1; $i <= 12; $i++){
            $suratTugasBulanan[$i] = SuratTugasPromotor::whereMonth('created_at', $i)->count() + suratTugasMandiriModel::count
        }

        return view('admin.dashboard.index2', compact(
            'totalUser',
            'totalLowongan',
            'totalSuratTugas'
        ));
    }
}
