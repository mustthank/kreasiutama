<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'detail_transaksi';
    protected $guarded =[];
    public $timestamps = false;
}
