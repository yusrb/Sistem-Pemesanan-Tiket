<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gerbong extends Model
{
    protected $table = 'gerbongs';
    protected $fillable = ['kereta_id', 'kode_gerbong', 'jumlah_kursi'];

    public function kereta(): BelongsTo
    {
        return $this->belongsTo(Kereta::class);
    }
}