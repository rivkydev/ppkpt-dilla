<?php

namespace App\Http\Controllers;
use App\Models\Berita;
use App\Models\Aduan;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    public function index()
    {
        
        $beritas = Berita::where('status', 'publish')->orderBy('tanggal', 'desc')->get();
        return view('home', compact('beritas'));
    }

    public function show($id)
    {
        $berita = Berita::findOrFail($id);
        $beritas = Berita::where('status', 'publish')->orderBy('tanggal', 'desc')->get();
        return view('berita', compact('berita', 'beritas'));
    }

    public function search(Request $request)
    {
        $kode = $request->get('kode');
        $aduan = null;
        $beritas = Berita::where('status', 'publish')->orderBy('tanggal', 'desc')->get();
        $aduans = collect();

        if ($kode) {
            $aduan = Aduan::where('kode_aduan', $kode)->first();
            if ($aduan) {
                $aduans = collect([$aduan]);
            }
        }

        return view('home', compact('aduan', 'beritas', 'aduans'));
    }
    
}
