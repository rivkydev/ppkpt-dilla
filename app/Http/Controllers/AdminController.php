<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helpers\AesHelper;
use App\Helpers\UrutanHelper;
use App\Models\User;
use App\Models\Berita;
use App\Models\Aduan;
use App\Models\Document;
use App\Models\Message;
use App\Models\Status;
use App\Models\Bobot;
use App\Models\Alternatif;
use App\Services\MARCOSService;


class AdminController extends Controller
{
    public function berita()
    {
        $beritas = Berita::where('status', 'publish')->orderBy('tanggal', 'desc')->get();
        $messages = Message::orderBy('id', 'desc')->get();
        
        $totalAduan = Aduan::count();
        $menungguVerifikasi = Aduan::whereHas('statuses', function ($query) {
            $query->where('label1', 'Menunggu Verifikasi Admin');
        })->whereDoesntHave('statuses', function ($query) {
            $query->whereIn('label1', ['Aduan Diteruskan ke Satgas', 'Aduan Ditolak']);
        })->count();
        $diprosesSatgas = Aduan::whereHas('statuses', function ($query) {
            $query->where('label1', 'Aduan Diteruskan ke Satgas');
        })->whereDoesntHave('statuses', function ($query) {
            $query->where('label1', 'Selesai Investigasi');
        })->count();
        $aduanSelesai = Aduan::whereHas('statuses', function ($query) {
            $query->where('label1', 'Selesai Investigasi');
        })->count();
        
        $aduanTerbaru = Aduan::latest()->take(5)->get();

        return view('admin.home', compact('beritas', 'messages', 'totalAduan', 'menungguVerifikasi', 'diprosesSatgas', 'aduanSelesai', 'aduanTerbaru'));
    }

    public function keloladokumen()
    {       
        $documents = Document::orderBy('id', 'asc')->get();
        return view('admin.dokumen.keloladokumen', compact('documents'));
    }

    public function showTambahDokumenForm()
    {
        return view('admin.dokumen.tambahdokumen');
    }

    public function storeDokumen(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'file' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ], [
            'judul.required' => 'Judul wajib diisi',
            'file.required' => 'File wajib diisi',
            'file.max' => 'File tidak boleh lebih dari 2MB',
        ]);

        try {
            if ($request->hasFile('file')) {
                $filedokumen = $request->file('file')->store('dokumen', 'public');
                $fileName = $filedokumen;
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan : ' . $e->getMessage());
        }

        Document::create([
            'judul' => $request->judul,
            'file' => $fileName,
        ]);

        return redirect()->route('admin.keloladokumen')->with('success', 'Dokumen berhasil ditambahkan.');
    }

    public function showEditDokumenForm($id)
    {
        $document = Document::findOrFail($id);
        return view('admin.dokumen.editdokumen', compact('document'));
    }

    public function updateDokumen(Request $request, $id)
    {
        $document = Document::findOrFail($id);

        $request->validate([
            'judul' => 'required',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ], [
            'judul.required' => 'Judul wajib diisi',
            'file.max' => 'File tidak boleh lebih dari 2MB',
        ]);

        try {
            if ($request->hasFile('file')) {
                // Hapus file lama jika ada
                if ($document->file && Storage::disk('public')->exists($document->file)) {
                    Storage::disk('public')->delete($document->file);
                }
                $filedokumen = $request->file('file')->store('dokumen', 'public');
                $fileName = $filedokumen;
            } else {
                $fileName = $document->file;
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        $document->update([
            'judul' => $request->judul,
            'file' => $fileName,
        ]);

        return redirect()->route('admin.keloladokumen')->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function deleteDokumen($id)
    {
        $document = Document::findOrFail($id);
        
        if ($document->file && Storage::disk('public')->exists($document->file)) {
            Storage::disk('public')->delete($document->file);
        }
        
        $document->delete();

        return redirect()->route('admin.keloladokumen')->with('success', 'Dokumen berhasil dihapus.');
    }   

    public function kelolapengguna()
    {
        $users = User::orderBy('fullname', 'asc')->get();
        return view('admin.pengguna.kelolapengguna', compact('users'));
    }

    public function showTambahPenggunaForm()    
    {
        return view('admin.pengguna.tambahpengguna');
    }

    public function storePengguna(Request $request)
    {
        // Validasi awal
        $rules = [
            'fullname' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|min:8|confirmed',
            'role' => 'required',
        ];

        // Email hanya wajib jika bukan admin
        if ($request->role !== 'admin' && $request->role !== 'satgas') {
            $rules['email'] = 'required|email|unique:users';
            $rules['nim_nidn'] = 'required';
            $rules['status'] = 'required';
        } else {
            // Email opsional untuk admin dan satgas
            $rules['email'] = 'nullable|email|unique:users';
            $rules['nim_nidn'] = 'nullable';
            $rules['status'] = 'nullable';
        }

        $request->validate($rules, [
            'fullname.required' => 'Nama Lengkap wajib diisi',
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'password.required' => 'Password wajib diisi',
            'password.confirmed' => 'Password tidak cocok',
            'role.required' => 'Pilih salah satu role',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah digunakan',
            'nim_nidn.required' => 'NIM/NIDN wajib diisi',
            'status.required' => 'Pilih salah satu status',
        ]);

        // Siapkan data
        $data = [
            'fullname' => $request->fullname,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password, 
            'role' => $request->role,
            'profile' => 'img/user.webp',
        ];

        if ($request->role === 'admin' && $request->role === 'satgas') {
            $data['status_verify'] = null;
            $data['nim_nidn'] = null;
            $data['status'] = null;
        } else {
            $data['status_verify'] = '0';
            $data['nim_nidn'] = $request->nim_nidn;
            $data['status'] = $request->status;
        }

        User::create($data);

        // Kembalikan response (misalnya redirect atau json)
        return redirect()->route('admin.kelolapengguna')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function showEditPenggunaForm($id)
    {
        $user = User::findOrFail($id);
        return view('admin.pengguna.editpengguna', compact('user'));
    }   

    public function updatePengguna(Request $request, $id) {
        $user = User::findOrFail($id);

        $rules = [
            'fullname' => 'required',
            'username' => 'required|unique:users,username,' . $user->id,
            'password' => 'nullable|confirmed',
        ];

        // Email hanya wajib jika bukan admin
        if ($user->role !== 'admin' && $user->role !== 'satgas') {
            $rules['email'] = 'required|email|unique:users,email,' . $user->id;
            $rules['nim_nidn'] = 'required';
            $rules['status'] = 'required';
        } else {
            // Email opsional untuk admin dan satgas
            $rules['email'] = 'nullable|email|unique:users,email,' . $user->id;
            $rules['nim_nidn'] = 'nullable';
            $rules['status'] = 'nullable';
        }


        $request->validate($rules, [
            'fullname.required' => 'Nama Lengkap wajib diisi',
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'password.confirmed' => 'Password tidak cocok',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah digunakan',
            'nim_nidn.required' => 'NIM/NIDN wajib diisi',
            'status.required' => 'Pilih salah satu status',
        ]);

        $data = [
            'fullname' => $request->fullname,
            'username' => $request->username,
            'email' => $request->email,
            'nim_nidn' => $request->nim_nidn,
            'status' => $request->status,
        ];

        if ($request->password) {
            $data['password'] = $request->password;
        }

        $user->update($data);

        return redirect()->route('admin.kelolapengguna')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function deletePengguna($id) {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.kelolapengguna')->with('success', 'Pengguna berhasil dihapus.');
    }

    public function kelolaberita()
    {
        $beritas = Berita::orderBy('id', 'asc')->get();
        return view('admin.berita.kelolaberita', compact('beritas'));
    }
    
    public function showTambahBeritaForm()
    {   
        return view('admin.berita.tambahberita');
    }

    public function storeBerita(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:80',
            'deskripsi' => [
            'required',
            function ($attribute, $value, $fail) {
                if (trim(strip_tags($value)) === '') {
                    $fail('Deskripsi wajib diisi.');
                }
            },

        ],
            'tanggal' => 'required',
            'penulis' => 'required',
            'gambar' => 'required|file|max:2048',
        ], [
            'judul.required' => 'Judul wajib diisi',
            'judul.max' => 'Judul tidak boleh lebih dari 80 karakter',
            'deskripsi.required' => 'Deskripsi wajib diisi',
            'tanggal.required' => 'Tanggal wajib diisi',
            'penulis.required' => 'Penulis wajib diisi',
            'gambar.required' => 'Gambar wajib diisi',
            'gambar.max' => 'Gambar tidak boleh lebih dari 2MB',
        ]);

        try {
            if ($request->hasFile('gambar')) {
                $fileberita = $request->file('gambar')->store('berita','public');
                $fileName = $fileberita;
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        Berita::create([
            'judul' => $request->judul,
            'isi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
            'penulis' => $request->penulis,
            'gambar' => $fileName,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.kelolaberita')->with('success', 'Berita berhasil ditambahkan.');
    }

    public function showEditBeritaForm($id)
    {
        $berita = Berita::findOrFail($id);
        return view('admin.berita.editberita', compact('berita'));
    }

    public function updateBerita(Request $request, $id) {
        $berita = Berita::findOrFail($id);
        $rules = [
            'judul' => 'required|string|max:80',
            'deskripsi' => [
            'required',
            function ($attribute, $value, $fail) {
                if (trim(strip_tags($value)) === '') {
                    $fail('Deskripsi wajib diisi.');
                }
            },

        ],
            'tanggal' => 'required',
            'penulis' => 'required',
            'gambar' => 'nullable|file|max:2048',
        ];

        $request->validate($rules, [
            'judul.required' => 'Judul wajib diisi',
            'judul.max' => 'Judul tidak boleh lebih dari 80 karakter',
            'deskripsi.required' => 'Deskripsi wajib diisi',
            'tanggal.required' => 'Tanggal wajib diisi',
            'penulis.required' => 'Penulis wajib diisi',
            'gambar.max' => 'Gambar tidak boleh lebih dari 2MB',
        ]);

        try {
            if ($request->hasFile('gambar')) {
                // Hapus gambar lama jika ada
                if ($berita->gambar && Storage::disk('public')->exists($berita->gambar)) {
                    Storage::disk('public')->delete($berita->gambar);
                }
                $fileberita = $request->file('gambar')->store('berita','public');
                $fileName = $fileberita;
            } else {
                $fileName = $berita->gambar;
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        $berita->update([
            'judul' => $request->judul,
            'isi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
            'penulis' => $request->penulis,
            'gambar' => $fileName,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.kelolaberita')->with('success', 'Berita berhasil diperbarui.');
    }

    public function deleteBerita($id) {
        $berita = Berita::findOrFail($id);
        
        if ($berita->gambar && Storage::disk('public')->exists($berita->gambar)) {
            Storage::disk('public')->delete($berita->gambar);
        }
        
        $berita->delete();

        return redirect()->route('admin.kelolaberita')->with('success', 'Berita berhasil dihapus.');
    }       

    public function kelolaformulir()
    {
        // Hanya tampilkan aduan yang belum diproses (belum ada status dengan label2 atau penolakan)
        $aduans = Aduan::with('statuses')
            ->orderBy('created_at', 'desc')
            ->whereDoesntHave('statuses', function($query) {
                $query->whereNotNull('label2')
                      ->orWhereNotNull('penolakan');
            })
            ->get();
        return view('admin.kelolaformulir', compact('aduans'));
    }

        
    public function alternatif()
    {
        return $this->hasOne(Alternatif::class, 'aduan_id');
    }


    public function kirimKeSatgas($id)
    {
        $aduan = Aduan::findOrFail($id);

        // Cek apakah sudah ada status untuk aduan ini
        $status = Status::where('aduan_id', $id)->first();

        if ($status) {
            // Update label2 dan status2 jika sudah ada
            $status->update([
                'label2' => 'Diteruskan Ke Satgas',
                'status2' => '[' . now()->format('d/m/Y') . '][' . now()->format('H:i') . '] - Laporan Anda Telah Diverifikasi Admin dan Diteruskan Kepada Satgas untuk Ditindaklanjuti'
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
            ->whereHas('aduan.lastStatus', function ($q) {
                $q->where('label2', 'Diteruskan Ke Satgas')   // ⬅ hanya yang di Satgas
                ->where(function ($qq) {
                    $qq->whereNull('label4')
                        ->orWhere('label4', '!=', 'Laporan Selesai');
                });
            })
            ->orderBy('aduan_id')
            ->get();


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


        return redirect()->route('admin.kelolaformulir')->with('success', 'Aduan berhasil dikirim ke Satgas.');
    }

    public function tolakAduan(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string',
        ], [
            'alasan_penolakan.required' => 'Alasan penolakan wajib diisi',
        ]);

        $aduan = Aduan::findOrFail($id);

        // Cek apakah sudah ada status untuk aduan ini
        $status = Status::where('aduan_id', $id)->first();

        if ($status) {
            // Update kolom penolakan jika sudah ada
            $status->update([
                'penolakan' => $request->alasan_penolakan,
                'label2' => 'Laporan Dikembalikan',
                'status2' => '[' . now()->format('d/m/Y') . '][' . now()->format('H:i') . '] - Laporan Anda dikembalikan karena informasi yang diberikan belum memenuhi ketentuan. Mohon lengkapi sesuai petunjuk dalam lampiran. <br> Lampiran: <a class="text-[#3B6BA2] font-semibold" href="#penolakan-' . $id . '">Lihat Detail</a>'
            ]);
        } 
        return redirect()->route('admin.kelolaformulir')->with('success', 'Aduan berhasil ditolak.');
    }

    public function komentar()
    {
        $messages = Message::orderBy('id', 'desc')->get();
        return view('admin.komentar', compact('messages'));
    }

public function decryptAduan(Request $request, $id)
{
    $start = microtime(true); // ⏱️ mulai hitung waktu

    $aduan = Aduan::findOrFail($id);

    try {
        if ($aduan->encrypted_aes_key) {
            $key = \App\Helpers\RsaHelper::decryptKey($aduan->encrypted_aes_key);
            $decrypted = [
                'nama_pelapor' => AesHelper::decryptWithKey($aduan->nama_pelapor, $key),
                'alamat_pelapor' => AesHelper::decryptWithKey($aduan->alamat_pelapor, $key),
                'email_pelapor' => AesHelper::decryptWithKey($aduan->email_pelapor, $key),
                'phone_pelapor' => AesHelper::decryptWithKey($aduan->phone_pelapor, $key),
                'hubungi' => AesHelper::decryptWithKey($aduan->hubungi, $key),
                'nama_korban' => AesHelper::decryptWithKey($aduan->nama_korban, $key),
                'alamat_korban' => AesHelper::decryptWithKey($aduan->alamat_korban, $key),
                'phone_korban' => AesHelper::decryptWithKey($aduan->phone_korban, $key),
                'nama_terlapor' => AesHelper::decryptWithKey($aduan->nama_terlapor, $key),
                'alamat_terlapor' => AesHelper::decryptWithKey($aduan->alamat_terlapor, $key),
                'phone_terlapor' => AesHelper::decryptWithKey($aduan->phone_terlapor, $key),
                'chronology' => AesHelper::decryptWithKey($aduan->chronology, $key),
                'karakteristik_terlapor' => AesHelper::decryptWithKey($aduan->karakteristik_terlapor, $key),
                'terlapor' => AesHelper::decryptWithKey($aduan->terlapor, $key),
                'warning' => AesHelper::decryptWithKey($aduan->warning, $key),
                'warning_detail' => AesHelper::decryptWithKey($aduan->warning_detail, $key),
                'lokasi' => AesHelper::decryptWithKey($aduan->lokasi, $key),
                'jenis_kelamin_korban' => AesHelper::decryptWithKey($aduan->jenis_kelamin_korban, $key),
                'status_korban' => AesHelper::decryptWithKey($aduan->status_korban, $key),
                'jenis_kelamin_terlapor' => AesHelper::decryptWithKey($aduan->jenis_kelamin_terlapor, $key),
                'status_terlapor' => AesHelper::decryptWithKey($aduan->status_terlapor, $key),
            ];
        } else {
            $key = $request->input('key');
            $decrypted = [
                'nama_pelapor' => AesHelper::decrypt($aduan->nama_pelapor, $key),
                'alamat_pelapor' => AesHelper::decrypt($aduan->alamat_pelapor, $key),
                'email_pelapor' => AesHelper::decrypt($aduan->email_pelapor, $key),
                'phone_pelapor' => AesHelper::decrypt($aduan->phone_pelapor, $key),
                'hubungi' => AesHelper::decrypt($aduan->hubungi, $key),
                'nama_korban' => AesHelper::decrypt($aduan->nama_korban, $key),
                'alamat_korban' => AesHelper::decrypt($aduan->alamat_korban, $key),
                'phone_korban' => AesHelper::decrypt($aduan->phone_korban, $key),
                'nama_terlapor' => AesHelper::decrypt($aduan->nama_terlapor, $key),
                'alamat_terlapor' => AesHelper::decrypt($aduan->alamat_terlapor, $key),
                'phone_terlapor' => AesHelper::decrypt($aduan->phone_terlapor, $key),
                'chronology' => AesHelper::decrypt($aduan->chronology, $key),
                'karakteristik_terlapor' => AesHelper::decrypt($aduan->karakteristik_terlapor, $key),
                'terlapor' => AesHelper::decrypt($aduan->terlapor, $key),
                'warning' => AesHelper::decrypt($aduan->warning, $key),
                'warning_detail' => AesHelper::decrypt($aduan->warning_detail, $key),
                'lokasi' => AesHelper::decrypt($aduan->lokasi, $key),
                'jenis_kelamin_korban' => AesHelper::decrypt($aduan->jenis_kelamin_korban, $key),
                'status_korban' => AesHelper::decrypt($aduan->status_korban, $key),
                'jenis_kelamin_terlapor' => AesHelper::decrypt($aduan->jenis_kelamin_terlapor, $key),
                'status_terlapor' => AesHelper::decrypt($aduan->status_terlapor, $key),
            ];
        }

        $end = microtime(true); // ⏱️ selesai
        $executionTime = $end - $start;

        return response()->json([
            'status' => 'success',
            'data' => $decrypted,
            'execution_time' => $executionTime // 🔥 kirim ke frontend
        ]);

    } catch (\Exception $e) {

        $end = microtime(true);
        $executionTime = $end - $start;

        return response()->json([
            'status' => 'error',
            'message' => 'Key salah atau gagal dekripsi',
            'execution_time' => $executionTime // tetap kirim biar bisa dianalisis
        ]);
    }
}

    public function arsip()
    {
        $aduans = Aduan::with('statuses')->orderBy('created_at', 'desc')->get();
        return view('admin.arsip', compact('aduans'));
    }
    
    public function detailaduan($id)
    {
        $aduan = Aduan::findOrFail($id);
        $status = Status::where('aduan_id', $id)->first();
        
        // Dekripsi menggunakan model method
        $aduan->decryptData();

        return view('admin.detailaduan', compact('aduan', 'status'));
    }

}