<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Tps;
use Illuminate\Http\Request;

use App\Models\GambarBukti;
use App\Models\HasilSuara;
use App\Models\Kelurahan;

class QuickCountController extends Controller
{
    public function index()
    {
        $hasilSuara = HasilSuara::with(['gambarBukti', 'tps.kelurahan.kecamatan'])
            ->where('Status', 'QuickCount')
            ->get();

        return view('QuickCount.index', [
            "hasilSuara" => $hasilSuara,
            'kecamatan' => \App\Models\Kecamatan::all(),
            'kelurahan' => \App\Models\Kelurahan::all(),
            'tps' => \App\Models\Tps::all(),
        ]);
    }
    public function insert()
    {
        $tps = Tps::with('kelurahan.kecamatan')->get();
        return view('QuickCount.AddForm', [
            'tps' => $tps,
        ]);
    }
    public function insertdata(Request $request)
    {

        $user = auth()->user();
        $request->validate([
            'tps' => 'required|exists:tps,id',
            'paslon1' => 'required|integer|min:0',
            'paslon2' => 'required|integer|min:0',
            'paslon3' => 'required|integer|min:0',
            'suara_tidak_sah' => 'required|integer|min:0',
            'Status' => 'required',
            'jumlah_kehadiran' => 'required',
            'gambar.*' => 'nullable|max:5120',
        ]);
        // Simpan ha;sil suara
        $hasilSuara = HasilSuara::create($request->only([
            'tps',
            'paslon1',
            'paslon2',
            'paslon3',
            'Status',
            'suara_tidak_sah',
            'jumlah_kehadiran',
        ]));
        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $gambar) {
                $path = $gambar->store('bukti', 'public');

                GambarBukti::create([
                    'hasil_suara_id' => $hasilSuara->id,
                    'path' => $path,
                ]);
            }
        }
        return redirect()->back()->with('success', 'Data Berhasil Disimpan');
    }
}
