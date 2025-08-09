<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penjual extends Model
{
    use HasFactory;

    protected $table = 'penjuals';

    protected $fillable = [
        'user_id',
        'nama',
        'foto_profil',
        'deskripsi',
        'foto_kios',
        'produk',
        'lokasi',
        'patokan',
        'kontak'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
