<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tmppembayaran extends Model
{
    protected $table = 'tmp_pembayaran';
    protected $guarded =[];
    public $timestamps = false;

    public function transaksi()
    {
        return $this->hasOne('App\Models\Transaksi','id_transaksi','id_transaksi');
        // return $this->hasOne('App\Phone', 'foreign_key', 'local_key');
    }
}
