<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\loginController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SatgasController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AduanController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\AhpController;
use App\Http\Controllers\MarcosController;



Route::get('/login', [loginController::class, 'index'])->name('login');
Route::post('/login', [loginController::class, 'auth']);
Route::get('/logout', [loginController::class, 'logout']);

Route::match(['get', 'post'], '/', [BeritaController::class, 'index'])->name('home');
Route::get('/berita/{id}', [BeritaController::class, 'show'])->name('berita');
Route::get('/editprofil', [ProfilController::class, 'index']);
Route::post('/editprofil', [ProfilController::class, 'update'])->name('editprofil.update');
Route::get('/', [BeritaController::class, 'search'])->name('home');

Route::post('/store', [UserController::class, 'store'])->name('messages.store');


Route::middleware('auth', 'role:admin')->group(function () {
    Route::get('/admin', [AdminController::class, 'berita'])->name('admin.home');

    Route::get('/admin/kelolaformulir', [AdminController::class, 'kelolaformulir'])->name('admin.kelolaformulir');
    Route::post('/admin/aduan/decrypt/{id}', [AdminController::class, 'decryptAduan']);
    Route::post('/admin/kelolaformulir/kirim/{id}', [AdminController::class, 'kirimKeSatgas'])->name('admin.kirimKeSatgas');
    Route::post('/admin/kelolaformulir/tolak/{id}', [AdminController::class, 'tolakAduan'])->name('admin.tolakAduan');
    
    Route::get('/admin/dokumen/keloladokumen', [AdminController::class, 'keloladokumen'])->name('admin.keloladokumen');
    Route::get('/admin/dokumen/tambahdokumen', [AdminController::class, 'showTambahDokumenForm'])->name('admin.tambahdokumen');
    Route::get('/admin/dokumen/editdokumen/{id}', [AdminController::class, 'showEditDokumenForm'])->name('admin.editdokumen');
    Route::put('/admin/dokumen/editdokumen/{id}', [AdminController::class, 'updateDokumen'])->name('admin.editdokumen.update');
    Route::delete('/admin/dokumen/keloladokumen/{id}', [AdminController::class, 'deleteDokumen'])->name('admin.keloladokumen.delete');
    Route::post('/admin/dokumen/tambahdokumen', [AdminController::class, 'storeDokumen'])->name('admin.keloladokumen.store');

    Route::get('/admin/pengguna/kelolapengguna', [AdminController::class, 'kelolapengguna'])->name('admin.kelolapengguna');
    Route::get('/admin/pengguna/tambahpengguna', [AdminController::class, 'showTambahPenggunaForm'])->name('admin.tambahpengguna');
    Route::get('/admin/pengguna/editpengguna/{id}', [AdminController::class, 'showEditPenggunaForm'])->name('admin.editpengguna');
    Route::put('/admin/pengguna/editpengguna/{id}', [AdminController::class, 'updatePengguna'])->name('admin.editpengguna.update');
    Route::delete('/admin/pengguna/kelolapengguna/{id}', [AdminController::class, 'deletePengguna'])->name('admin.kelolapengguna.delete');
    Route::post('/admin/pengguna/tambahpengguna', [AdminController::class, 'storePengguna'])->name('admin.kelolapengguna.store');

    Route::get('/admin/berita/kelolaberita', [AdminController::class, 'kelolaberita'])->name('admin.kelolaberita');
    Route::get('/admin/berita/tambahberita', [AdminController::class, 'showTambahBeritaForm'])->name('admin.tambahberita');
    Route::get('/admin/berita/editberita/{id}', [AdminController::class, 'showEditBeritaForm'])->name('admin.editberita');
    Route::put('/admin/berita/editberita/{id}', [AdminController::class, 'updateBerita'])->name('admin.editberita.update');
    Route::delete('/admin/berita/kelolaberita/{id}', [AdminController::class, 'deleteBerita'])->name('admin.kelolaberita.delete');
    Route::post('/admin/berita/tambahberita', [AdminController::class, 'storeBerita'])->name('admin.kelolaberita.store');
    
    Route::get('/admin/komentar', [AdminController::class, 'komentar'])->name('admin.komentar');
    Route::get('/admin/arsip', [AdminController::class, 'arsip'])->name('admin.arsip');
    Route::get('/admin/detailaduan/{id}', [AdminController::class, 'detailaduan'])->name('admin.detailaduan');
});

Route::middleware('auth', 'role:pelapor')->group(function () { 
    Route::get('/user', [UserController::class, 'berita'])->name('user.home');
    Route::post('/user', [UserController::class, 'storeAduan'])->name('aduan.store');
    Route::get('/user/hasilinvestigasi/{kode_aduan}', [UserController::class, 'hasilinvestigasi'])->name('user.hasilinvestigasi');
});

Route::middleware('auth', 'role:satgas')->group(function () {
    Route::get('/satgas', [SatgasController::class, 'home'])->name('satgas.home');
    Route::get('/satgas/investigasi/{id}', [SatgasController::class, 'investigasi'])->name('satgas.investigasi');
    
    Route::get('/satgas/laporanmasuk', [SatgasController::class, 'laporanmasuk'])->name('satgas.laporanmasuk');
    Route::get('/satgas/laporanditangani', [SatgasController::class, 'laporanditangani'])->name('satgas.laporanditangani');
    Route::get('/satgas/detaillaporan/{id}', [SatgasController::class, 'detaillaporan'])->name('satgas.detaillaporan');
    Route::post('/satgas/terimaaduan/{id}', [SatgasController::class, 'terimaaduan'])->name('satgas.terimaaduan');
    Route::post('/satgas/investigasi/{id}', [SatgasController::class, 'investigasiStore'])->name('satgas.investigasiStore');
    Route::get('/satgas/detailinvestigasi/{kode_aduan}', [SatgasController::class, 'detailinvestigasi'])->name('satgas.detailinvestigasi');

    Route::get('/satgas/laporanselesai', [SatgasController::class, 'laporanselesai'])->name('satgas.laporanselesai');
    Route::get('/satgas/berita', [SatgasController::class, 'berita'])->name('satgas.berita');
    Route::get('/satgas/berita/{id}', [SatgasController::class, 'beritaDetail'])->name('satgas.beritaDetail');

    Route::post('/satgas/aduan/decrypt/{id}', [SatgasController::class, 'decryptAduan']);
    Route::get('/satgas/perhitungan', [AhpController::class, 'calculate'])->name('satgas.perhitungan');
});

Route::get('/download-pernyataan', function () {
    return response()->download(
        public_path('img/surat_pernyataan.pdf'),
        'Surat_Pernyataan_Lapor_Aman_ITH.pdf'
    );
});



Route::get('/docs', function () {
    $content = file_get_contents(base_path('docs/DOKUMENTASI_SKRIPSI.md'));
    
    // Protect math formulas from being mangled by markdown parser
    $content = preg_replace_callback('/\$([^\$]+)\$/', function($m) {
        return '@@MATH@@' . base64_encode($m[1]) . '@@MATH@@';
    }, $content);
    
    $html = Illuminate\Support\Str::markdown($content);
    
    // Restore math formulas
    $html = preg_replace_callback('/@@MATH@@(.*?)@@MATH@@/', function($m) {
        return '$' . base64_decode($m[1]) . '$';
    }, $html);

    return view('docs', ['html' => $html]);
});