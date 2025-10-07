<?php

namespace App\Models;

use App\Models\Penumpang;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $table = 'users';
    protected $fillable = ['nama', 'nik', 'no_telepon', 'email', 'password', 'role', 'foto'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime'];

    public function pemesanans(): HasMany
    {
        return $this->hasMany(Pemesanan::class);
    }

    public function penumpangRecord(): HasOne
    {
        return $this->hasOne(Penumpang::class, 'user_id', 'id');
    }
}