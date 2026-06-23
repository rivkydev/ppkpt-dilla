<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\AesHelper;
use App\Helpers\RsaHelper;

class Aduan extends Model
{

    protected $fillable = [
        'user_id',
        'kode_aduan',
        'nama_pelapor',
        'alamat_pelapor',
        'pernyataan_pelapor',
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
        'tanggal_peristiwa',
        'category',
        'chronology',
        'bukti_pelaporan',
        'lokasi',
        'icon',
        'bersedia',
        'prioritas',
        'peringkat',
        'nilai',
        'encrypted_aes_key'
    ];

    protected $casts = [
        'tanggal_peristiwa' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statuses()
    {
        return $this->hasMany(Status::class)->orderBy('created_at', 'asc');
    }

    public function investigation()
    {
        return $this->hasOne(Investigation::class, 'kode_aduan', 'kode_aduan');
    }
    
    public function alternatif()
    {
        return $this->hasOne(Alternatif::class, 'aduan_id', 'id');
    }

    public function bobot()
    {
        return $this->hasOne(Bobot::class, 'aduan_id', 'id');
    }

    public function lastStatus()
    {
        return $this->hasOne(Status::class)->latestOfMany();
    }

    public function decryptData()
    {
        $key = $this->encrypted_aes_key ? RsaHelper::decryptKey($this->encrypted_aes_key) : 'PPKPTith';
        
        $fields = [
            'lokasi', 'nama_pelapor', 'alamat_pelapor', 'email_pelapor', 'phone_pelapor',
            'hubungi', 'nama_korban', 'alamat_korban', 'phone_korban', 'status_korban',
            'jenis_kelamin_korban', 'nama_terlapor', 'alamat_terlapor', 'phone_terlapor',
            'status_terlapor', 'karakteristik_terlapor', 'jenis_kelamin_terlapor',
            'chronology', 'warning', 'warning_detail', 'bersedia'
        ];

        foreach ($fields as $field) {
            if (!empty($this->$field)) {
                $this->$field = AesHelper::decrypt($this->$field, $key);
            }
        }
        
        return $this;
    }
}
