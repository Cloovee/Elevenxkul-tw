<?php

namespace App\Models;

// Note: Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'berita_dilihat_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'string',
        ];
    }

    public function pembina()
    {
        return $this->hasOne(Pembina::class, 'id_user');
    }

    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'id_user');
    }
}