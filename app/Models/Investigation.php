<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Investigation extends Model
{
     protected $fillable = [
        'aduan_id',
        'kode_aduan',
        'tanggal',
        'jenis_kekerasan',
        'lokasi_kejadian',
        'nama_korban',
        'status_korban',
        'nama_terlapor',
        'status_terlapor',
        'nama_saksi',
        'keterangan_saksi',
        'proses',
        'catatan_proses',
        'kronologi',
        'wawancara_korban',
        'wawancara_terlapor',
        'wawancara_saksi',
        'fakta_terbukti',
        'fakta_tidak_terbukti',
        'file_terbukti',
        'tindak_lanjut',
        'catatan_tindak_lanjut',
        'hasil_akhir',
        'kesimpulan',
        'status_investigasi',
    ];

    protected $casts = [
    'proses' => 'array',
    'tindak_lanjut' => 'array',
];

// In Investigation.php
public function aduan()
{
    return $this->belongsTo(Aduan::class, 'kode_aduan', 'kode_aduan');
}
}
