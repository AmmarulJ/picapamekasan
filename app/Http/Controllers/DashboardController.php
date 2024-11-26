<?php

namespace App\Http\Controllers;

use App\Models\HasilSuara;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Tps;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {

        $totalQuickCountPaslon1 = HasilSuara::where('Status', "QuickCount")->sum("paslon1");
        $totalQuickCountPaslon2 = HasilSuara::where('Status', "QuickCount")->sum("paslon2");
        $totalQuickCountPaslon3 = HasilSuara::where('Status', "QuickCount")->sum("paslon3");
        $totalQuickCountTidakSah = HasilSuara::where('Status', "QuickCount")->sum("suara_tidak_sah");
        $totalQuickCount = $totalQuickCountTidakSah + $totalQuickCountPaslon3 + $totalQuickCountPaslon2 + $totalQuickCountPaslon1;
        $totalRealCountPaslon1 = HasilSuara::where('Status', "RealCount")->sum("paslon1");
        $totalRealCountPaslon2 = HasilSuara::where('Status', "RealCount")->sum("paslon2");
        $totalRealCountPaslon3 = HasilSuara::where('Status', "RealCount")->sum("paslon3");
        $totalRealCountTidakSah = HasilSuara::where('Status', "RealCount")->sum("suara_tidak_sah");
        $totalRealCount = $totalRealCountTidakSah + $totalRealCountPaslon3 + $totalRealCountPaslon2 + $totalRealCountPaslon1;


        $totalQuickCountPerTps = Kecamatan::with('kelurahan.tps.hasilSuara')
            ->get()
            ->mapWithKeys(function ($kecamatan) {
                $totals = $kecamatan->kelurahan->flatMap->tps->flatMap->hasilSuara->where('Status', 'QuickCount');
                return [
                    $kecamatan->nama => [
                        'total_paslon1' => $totals->sum('paslon1'),
                        'total_paslon2' => $totals->sum('paslon2'),
                        'total_paslon3' => $totals->sum('paslon3'),
                        'total_tidak_sah' => $totals->sum('suara_tidak_sah'),
                    ],
                ];
            });

        $totalRealCountPerTps = Kecamatan::with('kelurahan.tps.hasilSuara')
            ->get()
            ->mapWithKeys(function ($kecamatan) {
                $totals = $kecamatan->kelurahan->flatMap->tps->flatMap->hasilSuara->where('Status', 'RealCount');
                return [
                    $kecamatan->nama => [
                        'total_paslon1' => $totals->sum('paslon1'),
                        'total_paslon2' => $totals->sum('paslon2'),
                        'total_paslon3' => $totals->sum('paslon3'),
                        'total_tidak_sah' => $totals->sum('suara_tidak_sah'),
                    ],
                ];
            });

        return view('Dashboards.index', [
            "title" => "Dashboard",
            "totalQuickCountPaslon1" => $totalQuickCountPaslon1,
            "totalQuickCountPaslon2" => $totalQuickCountPaslon2,
            "totalQuickCountPaslon3" => $totalQuickCountPaslon3,
            "totalQuickCountTidakSah" => $totalQuickCountTidakSah,
            "totalQuickCount" => $totalQuickCount,
            "totalRealCountPaslon1" => $totalRealCountPaslon1,
            "totalRealCountPaslon2" => $totalRealCountPaslon2,
            "totalRealCountPaslon3" => $totalRealCountPaslon3,
            "totalRealCountTidakSah" => $totalRealCountTidakSah,
            "totalRealCount" => $totalRealCount,
            "totalQuickCountPerTps" => $totalQuickCountPerTps,
            "totalRealCountPerTps" => $totalRealCountPerTps,
        ]);
    }
}
