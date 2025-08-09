<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
    ];

    public function penjual()
    {
        return $this->hasOne(Penjual::class);
    }
}
