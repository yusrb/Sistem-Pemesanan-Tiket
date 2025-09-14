<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jadwal extends Model
{
    protected $table = 'jadwals';
    protected $fillable = ['kereta_id', 'stasiun_awal', 'stasiun_akhir', 'tanggal', 'jam_berangkat', 'jam_sampai', 'harga'];

    public function kereta(): BelongsTo
    {
        return $this->belongsTo(Kereta::class);
    }

    public function pemesanans(): HasMany
    {
        return $this->hasMany(Pemesanan::class);
    }

    protected $casts = [
        'tanggal' => 'datetime',
    ];
}