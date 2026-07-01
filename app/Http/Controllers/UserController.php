<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Helpers\AesHelper;
use App\Models\Verify;
use App\Mail\OtpMail;
use App\Models\Berita;
use App\Models\Aduan;
use App\Models\Status;
use App\Models\Message;
use App\Models\Investigation;
use App\Services\MARCOSService;
use Illuminate\Validation\ValidationException;
use App\Models\Bobot;
use App\Models\Alternatif;


class UserController extends Controller
{


    public function storeAduan(Request $request)
    {
        $validatedData = $request->validate([
            'nama_pelapor' => 'required|string|max:255',
            'alamat_pelapor' => 'required|string|max:255',
            'pernyataan_pelapor' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'email_pelapor' => 'required|email|max:255',
            'phone_pelapor' => 'required|string|max:20',
            'hubungi' => 'required|string|max:20',
            'hubungi_akun' => 'nullable|string|max:255',
            'nama_korban' => 'required|string|max:255',
            'jenis_kelamin_korban' => 'required|string|max:20',
            'alamat_korban' => 'nullable|string|max:255',
            'phone_korban' => 'nullable|string|max:20',
            'status_korban' => 'required|string|max:20',
            'nama_terlapor' => 'required|string|max:255',
            'jenis_kelamin_terlapor' => 'required|string|max:20',
            'alamat_terlapor' => 'nullable|string|max:255',
            'phone_terlapor' => 'nullable|string|max:20',
            'status_terlapor' => 'required|string|max:20',
            'karakteristik_terlapor' => 'required|string|max:255',
            'terlapor' => 'required|string|max:20',
            'warning' => 'required|string|max:20',
            'warning_detail' => 'nullable|string|max:255',
            'tanggal_peristiwa' => 'required|date',
            'category' => 'required|string|max:255',
            'chronology' => 'required|string',
            'bukti_pelaporan' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi,mp3,wav,pdf,ogg|max:10240',
            'lokasi' => 'required|string|max:255',
            'bersedia' => 'required|string|max:255',
        ]);

        if (
            in_array($validatedData['hubungi'], ['Facebook', 'Instagram']) &&
            !empty($validatedData['hubungi_akun'])
        ) {
            $validatedData['hubungi'] .= ' : ' . $validatedData['hubungi_akun'];
        }

        unset($validatedData['hubungi_akun']);

        $validatedData['user_id'] = Auth::id();
        $validatedData['icon'] = 'fa-solid fa-file-circle-check';

        $fieldsToEncrypt = [
            'nama_pelapor',
            'alamat_pelapor',
            'email_pelapor',
            'phone_pelapor',
            'hubungi',
            'nama_korban',
            'jenis_kelamin_korban',
            'alamat_korban',
            'phone_korban',
            'status_korban',
            'nama_terlapor',
            'jenis_kelamin_terlapor',
            'alamat_terlapor',
            'phone_terlapor',
            'status_terlapor',
            'karakteristik_terlapor',
            'terlapor',
            'warning',
            'warning_detail',
            'chronology',
            'lokasi',
            'bersedia'
        ];

        // HYBRID ENCRYPTION: Generate AES Key and Encrypt with RSA
        $aesKey = AesHelper::generateKey();
        $validatedData['encrypted_aes_key'] = \App\Helpers\RsaHelper::encryptKey($aesKey);

        foreach ($fieldsToEncrypt as $field) {
            if (isset($validatedData[$field])) {
                $validatedData[$field] = AesHelper::encryptWithKey($validatedData[$field], $aesKey);
            }
        }

        try {
            if ($request->hasFile('pernyataan_pelapor')) {
                $file = $request->file('pernyataan_pelapor');
                $filePath = $file->store('aduan/pernyataan', 'public');
                $fullPath = storage_path('app/public/' . $filePath);
                $fileContent = file_get_contents($fullPath);
                $encryptedContent = AesHelper::encryptWithKey($fileContent, $aesKey);
                file_put_contents($fullPath, $encryptedContent);
                $validatedData['pernyataan_pelapor'] = $filePath;
            }

            if ($request->hasFile('bukti_pelaporan')) {
                $file = $request->file('bukti_pelaporan');
                $filePath = $file->store('aduan/bukti', 'public');
                $fullPath = storage_path('app/public/' . $filePath);
                $fileContent = file_get_contents($fullPath);
                $encryptedContent = AesHelper::encryptWithKey($fileContent, $aesKey);
                file_put_contents($fullPath, $encryptedContent);
                $validatedData['bukti_pelaporan'] = $filePath;
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal upload file: ' . $e->getMessage());
        }
        $aduan = Aduan::create($validatedData);
        $service = new MARCOSService();

        $nilai = $service->hitungNilaiAlternatif($request);

        Alternatif::create([
            'aduan_id' => $aduan->id,
            'kriteria1' => $nilai['c1'],
            'kriteria2' => $nilai['c2'],
            'kriteria3' => $nilai['c3'],
            'kriteria4' => $nilai['c4'],
            'kriteria5' => $nilai['c5'],
            'kriteria6' => $nilai['c6'],
        ]);

        $aduan->kode_aduan = 'PPKPT' . $aduan->id . date('dy') . rand(10, 99);
        $aduan->save();
        
        
        Status::create([
            'aduan_id' => $aduan->id,
            'label1'   => 'Menunggu Verifikasi Admin',
            'status1'  => '[' . now()->format('d/m/Y') . '][' . now()->format('H:i') .
                '] - Laporan Anda berhasil dikirim dan sedang menunggu verifikasi dari admin.'
        ]);

        return redirect()->route('aduan.store')->with('success', 'Aduan berhasil dikirim');
    } 

    public function berita()
    {   
        \Carbon\Carbon::setLocale('id');

        $aduans = Aduan::with('statuses') // ambil aduan + statusnya
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        $beritas = Berita::where('status', 'publish')
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('user.home', compact('beritas', 'aduans'));
    }

    private function maskEmail($email)
    {
        $atPos = strpos($email, '@');
        $username = substr($email, 0, $atPos);
        $domain = substr($email, $atPos);

        // Pisahkan: 3 huruf awal + masking + 1 huruf terakhir
        if (strlen($username) > 4) {
            $first1 = substr($username, 0, 1);
            $last2 = substr($username, -2);
            $maskedMiddle = str_repeat('*', strlen($username) - 3);
            $maskedEmail = $first1 . $maskedMiddle . $last2 . $domain;
        } else {
            // Kalau username terlalu pendek (<=4), tampilkan apa adanya
            $maskedEmail = $username . $domain;
        }

        return $maskedEmail;
    }

 

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nama' => 'required',
                'email' => 'required|email',
                'pesan' => 'required',
            ], [
                'nama.required' => 'Nama wajib diisi.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Email tidak valid.',
                'pesan.required' => 'Pesan wajib diisi.',
            ]);
    
            Message::create($validatedData);
    
            if (auth()->check()) {
                return redirect()->route('user.home')->with('success', 'Pesan berhasil dikirim!');
            } else {
                return redirect()->route('home')->with('success', 'Pesan berhasil dikirim!');
            }
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator, 'message')
                ->withInput();
        }
    }

    public function hasilinvestigasi($kode_aduan)
    {   
        $aduan = Aduan::where('kode_aduan', $kode_aduan)->first();
        $investigasi = Investigation::where('kode_aduan', $kode_aduan)->first();
        return view('user.hasilinvestigasi', compact('investigasi', 'aduan'));
    }
    
}
