<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'no_hp',
        'no_hp2',
        'nama_no_hp2',
        'relasi_no_hp2',
        'NIK',
        'Norek',
        'Nama_Ibu',
        'Pekerjaan',
        'Gaji',
        'alamat',
        'kode_bank',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        // ✅ REMOVE 'password' => 'hashed' (only for Laravel 10+)
    ];

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'kode_bank', 'kode_bank');
    }
}
