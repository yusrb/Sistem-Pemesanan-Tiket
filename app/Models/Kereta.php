<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kereta extends Model
{
    protected $table = 'keretas';
    protected $fillable = ['nama', 'jumlah_gerbong'];

    public function gerbongs(): HasMany
    {
        return $this->hasMany(Gerbong::class);
    }

    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class);
    }

    public function getJumlahGerbongAttribute()
    {
        return $this->gerbongs()->count();
    }
}