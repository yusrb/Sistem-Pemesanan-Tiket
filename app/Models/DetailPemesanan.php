<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPemesanan extends Model
{
    use HasFactory;

    protected $fillable = ['pemesanan_id', 'penumpang_id', 'gerbong_id', 'kode', 'status'];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class);
    }

    public function penumpang()
    {
        return $this->belongsTo(Penumpang::class);
    }

    public function gerbong()
    {
        return $this->belongsTo(Gerbong::class);
    }
}