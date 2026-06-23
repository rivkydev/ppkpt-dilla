<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Aduan;
use App\Models\Status;
use App\Models\Bobot;
use App\Models\Alternatif;
use App\Models\Investigation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helpers\AesHelper;
use App\Services\MARCOSService;




class SatgasController extends Controller
{
    public function home()
    {
        $userId = Auth::id();

        $totalAduanDitugaskan = Aduan::whereHas('statuses', function ($query) use ($userId) {
            $query->where('diterima_oleh', $userId);
        })->count();

        $sedangInvestigasi = Aduan::whereHas('statuses', function ($query) use ($userId) {
            $query->where('diterima_oleh', $userId);
        })->whereDoesntHave('statuses', function ($query) {
            $query->where('label1', 'Selesai Investigasi');
        })->count();

        $aduanSelesai = Aduan::whereHas('statuses', function ($query) use ($userId) {
            $query->where('diterima_oleh', $userId)->where('label1', 'Selesai Investigasi');
        })->count();

        $aduanTerbaru = Aduan::whereHas('statuses', function ($query) use ($userId) {
            $query->where('diterima_oleh', $userId);
        })->latest()->take(5)->get();

        return view('satgas.home', compact('totalAduanDitugaskan', 'sedangInvestigasi', 'aduanSelesai', 'aduanTerbaru'));
    }
    
    public function berita()
    {
        $beritas = Berita::where('status', 'publish')
            ->orderBy('tanggal', 'desc')
            ->get();


        return view('satgas.berita', compact('beritas'));
    }

    public function beritaDetail($id)
    {
        $berita = Berita::findOrFail($id);
        return view('satgas.selengkapnya', compact('berita'));
    }

    public function laporanditangani()
    {
        $userId = Auth::id();

        $aduans = Aduan::whereHas('statuses', function ($query) use ($userId) {
                $query->where('diterima_oleh', $userId);
            })
            ->with(['statuses' => function ($query) use ($userId) {
                $query->where('diterima_oleh', $userId);
            }])
            ->latest()
            ->get();

        return view('satgas.laporanditangani', compact('aduans'));
    }

    public function detaillaporan($id)
    {
        $aduan = Aduan::where('kode_aduan', $id)->firstOrFail();
        
        // Dekripsi menggunakan model method
        $aduan->decryptData();

        

        return view('satgas.detaillaporan', compact('aduan'));
    }

    public function terimaaduan($id)
    {
        $aduan = Aduan::findOrFail($id);

        // Cek apakah sudah ada status untuk aduan ini
        $status = Status::where('aduan_id', $id)->first();

        // Ambil user yang sedang login
        $userId = auth()->id(); // atau Auth::id()

        if ($status) {
            // Update label2 dan status2 jika sudah ada
            $status->update([
                'label3' => 'Investigasi Lapangan',
                'status3' => '[' . now()->format('d/m/Y') . '][' . now()->format('H:i') . '] - Laporan Anda sedang dalam proses investigasi lapangan',
                'diterima_oleh' => $userId
            ]);
        }

        $bobot = Bobot::first(); 

        $w = [
            $bobot->c1,
            $bobot->c2,
            $bobot->c3,
            $bobot->c4,
            $bobot->c5,
            $bobot->c6,
        ];

        $alternatifs = Alternatif::with(['aduan.lastStatus'])
    ->where('aduan_id', '!=', $id) // ⬅ aduan yang diterima tidak ikut dihitung
    ->whereHas('aduan.lastStatus', function ($q) {
        $q->where('label2', 'Diteruskan Ke Satgas')
        ->where(function ($qq) {
            $qq->whereNull('label4')
                ->orWhere('label4', '!=', 'Laporan Selesai');
        });
    })
    ->orderBy('aduan_id')
    ->get();

    if ($alternatifs->isEmpty()) {
   return redirect()->route('satgas.laporanditangani')->with('success', 'Aduan berhasil diterima');
}


        $L = [];
        $map = [];   // index MARCOS → aduan_id

        foreach ($alternatifs as $i => $alt) {
            $L[] = [
                $alt->kriteria1,
                $alt->kriteria2,
                $alt->kriteria3,
                $alt->kriteria4,
                $alt->kriteria5,
                $alt->kriteria6,
            ];

            $map[$i] = $alt->aduan_id;
        }

        
        $type = [
        'cost',    // C1
        'benefit', // C2
        'benefit', // C3
        'benefit', // C4
        'benefit', // C5
        'benefit', // C6
        ];

$type = array_values($type);
$w    = array_values($w);
$L    = array_values($L);

$marcos = new MARCOSService();

// Step 2
[$AI, $AAI] = $marcos->idealAntiIdeal($L, $type);

// Step 3 — Extended matrix
$L_ext = array_merge([$AAI], $L, [$AI]);

// Step 4
$N  = $marcos->normalisasi($L_ext, $AI, $type);
$WN = $marcos->normalisasiBerbobot($N, $w);

// Step 5
$S_all = $marcos->nilaiKegunaan($WN);

// Step 6
[$Cplus, $Cminus] = $marcos->derajatKegunaan($S_all, count($L));

// Step 7
$ranking = $marcos->fungsiKegunaan($Cplus, $Cminus);

// Selanjutnya proses ranking dan update data seperti yang sudah kamu lakukan sebelumnya.

        $total = count($ranking);

        $i = 1;

        foreach ($ranking as $index => $score) {

            $aduanId = $map[$index];

            Aduan::where('id', $aduanId)->update([
                'nilai'     => round($score, 4),
                'peringkat' => $i,
                'prioritas' => angkaKeUrutan($i), // ⬅ ini yang diganti
            ]);

            $i++;
        }


        
        return redirect()->route('satgas.laporanditangani')->with('success', 'Aduan berhasil diterima');
    }

    public function investigasi($id)
    {
        $aduan = Aduan::findOrFail($id);
        $investigasi = Investigation::where('kode_aduan', $aduan->kode_aduan)->first();

        // Dekripsi menggunakan model method
        $aduan->decryptData();
        
        return view('satgas.investigasi', compact('aduan', 'investigasi'));
    }

    public function investigasiStore(Request $request, $id)
    {
        // Ambil status dari tombol submit
        $status = $request->status_investigasi; // draft | publish

        // Validasi dasar
        $rules = [
            'tanggal' => 'required|date',
            'jenis_kekerasan' => 'required',
            'lokasi_kejadian' => 'required',
            'nama_korban' => 'required',
            'status_korban' => 'required',
            'nama_terlapor' => 'required',
            'status_terlapor' => 'required',

            // opsional umum
            'nama_saksi' => 'nullable',
            'keterangan_saksi' => 'nullable',
            'catatan_proses' => 'nullable',
            'catatan_tindak_lanjut' => 'nullable',
            'wawancara_korban' => 'nullable',
            'wawancara_saksi' => 'nullable',
            'wawancara_terlapor' => 'nullable',
            'fakta_terbukti' => 'nullable',
            'fakta_tidak_terbukti' => 'nullable',
            'file_terbukti' => 'nullable|file',
        ];

        // Validasi kondisional
        if ($status === 'publish') {
            $rules += [
                'proses' => 'required|array',
                'kronologi' => 'required',
                'tindak_lanjut' => 'required|array',
                'hasil_akhir' => 'required',
                'kesimpulan' => 'required',
            ];
        } else {
            $rules += [
                'proses' => 'nullable|array',
                'kronologi' => 'nullable',
                'tindak_lanjut' => 'nullable|array',
                'hasil_akhir' => 'nullable',
                'kesimpulan' => 'nullable',
            ];
        }

        $data = $request->validate($rules);

        // Ambil aduan
        $aduan = Aduan::findOrFail($id);

        // Data tambahan sistem
        $data['kode_aduan'] = $aduan->kode_aduan;
        $data['status_investigasi'] = $status;
        $data['tanggal'] = Carbon::parse($request->tanggal)->format('Y-m-d');

        // Checkbox → JSON
        $data['proses'] = $request->proses ? json_encode($request->proses) : null;
        $data['tindak_lanjut'] = $request->tindak_lanjut ? json_encode($request->tindak_lanjut) : null;

        // Upload file
        if ($request->hasFile('file_terbukti')) {
            $data['file_terbukti'] = $request->file('file_terbukti')
                ->store('bukti', 'public');
        }

        

        // Pastikan aduan_id ada di data
        $data['aduan_id'] = $aduan->id;

        // 🔥 CREATE atau UPDATE
        Investigation::updateOrCreate(
            [
                'aduan_id' => $aduan->id,
                'kode_aduan' => $aduan->kode_aduan
            ], // kunci pencarian
            $data
        );

        $urlDetail = route('user.hasilinvestigasi', $aduan->kode_aduan);

        if ($status === 'publish') {

            $statusText =
                '[' . now()->format('d/m/Y') . '][' . now()->format('H:i') . '] - '
                . 'Investigasi telah selesai dan laporan dinyatakan '
                . ucwords(str_replace('_', ' ', $request->hasil_akhir)) . '.'
                . '<br> Lampiran: '
                . '<a class="text-[#3B6BA2] font-semibold" href="' . $urlDetail . '">'
                . 'Lihat Detail</a>';

            Status::updateOrCreate(
                ['aduan_id' => $aduan->id],
                [
                    'label4'  => 'Laporan Selesai',
                    'status4' => $statusText,
                ]
            );
        }

        return redirect()
            ->route('satgas.laporanditangani')
            ->with(
                'success',
                $status === 'publish'
                    ? 'Investigasi berhasil dipublikasikan'
                    : 'Draft investigasi berhasil disimpan'
            );
    }

    public function detailinvestigasi($kode_aduan)
    {
        $investigasi = Investigation::where('kode_aduan', $kode_aduan)->firstOrFail();

        $status = Status::with('userPenerima')
        ->where('aduan_id', $investigasi->aduan_id)
        ->first();
            

        return view('satgas.detailinvestigasi', compact('investigasi', 'status'));
    }

    public function laporanselesai()
    {
        // Ambil aduan yang status terakhirnya 'Laporan Selesai'
        $aduans = Aduan::with('statuses.userPenerima')
    ->whereHas('statuses', function ($query) {
        $query->where('label4', 'Laporan Selesai');
    })
    ->orderBy('tanggal_peristiwa', 'desc')
    ->get();

        return view('satgas.laporanselesai', compact('aduans'));
    }

    public function laporanmasuk()
    {
        $aduans = Aduan::with('statuses')->whereHas('statuses', function ($query) {
            $query->where('label2', 'Diteruskan Ke Satgas')
                  ->where(function ($q) {
                      $q->whereNull('label3')
                        ->orWhere('label3', '');
                  });
        })
        ->orderBy('nilai', 'desc')
        ->get();

        foreach ($aduans as $aduan) {
            $aduan->decryptData();
        }

        return view('satgas.laporanmasuk', compact('aduans'));
    }

  
}
