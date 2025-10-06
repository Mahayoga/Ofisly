<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\DaftarLowongan;
// use App\Models\LowonganPekerjaan;
use App\Models\SuratTugasMandiri;
use App\Models\SuratTugasPromotor;
use App\Models\SuratTugasPenggantiDriver;
use App\Models\suratPenempatanPramubaktiMandiri;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalLowongan = DaftarLowongan::count();
        $totalSuratMandiri = SuratTugasMandiri::count();
    }
}
