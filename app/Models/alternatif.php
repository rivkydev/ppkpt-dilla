<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Aduan;

class Alternatif extends Model
{
    protected $fillable = [
    'aduan_id',
    'kriteria1',
    'kriteria2',
    'kriteria3',
    'kriteria4',
    'kriteria5',
    'kriteria6',
];

    /**
     * Get the aduan that owns the alternatif.
     */
    public function aduan()
    {
        return $this->belongsTo(Aduan::class, 'aduan_id');
    }
}
