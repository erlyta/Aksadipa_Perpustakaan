<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // nonaktifkan timestamps karena tabel hanya punya created_at
    public $timestamps = true;

    // kolom yang bisa diisi massal
    protected $fillable = [
        'username',
        'full_name',
        'email',
        'avatar',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
    ];
}
