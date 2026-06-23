<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'statuses';
    protected $fillable = [
        'aduan_id',
        'label1',
        'status1',
        'label2',
        'status2',
        'label3',
        'status3',
        'label4',
        'status4',
        'label5',
        'status5',
        'penolakan',
        'diterima_oleh',
    ];

   
    public function status()
    {
        return $this->hasOne(Status::class, 'aduan_id');
    }

    public function userPenerima()
    {
        return $this->belongsTo(User::class, 'diterima_oleh');
    }
}
