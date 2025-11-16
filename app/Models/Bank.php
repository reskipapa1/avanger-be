<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = 'bank';
    protected $primaryKey = 'kode_bank';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['kode_bank', 'nama'];
}
