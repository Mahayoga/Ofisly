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
        $totalUsers = User::count();
        $totalLowongan = LowonganPekerjaanModel::count();

        $totalSuratTugas = SuratTugasPromotor
    }
}
