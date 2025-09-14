<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penumpang extends Model
{
    protected $table = 'penumpangs';
    protected $fillable = ['nik', 'nama', 'user_id'];

    public function detailPemesanans()
    {
        return $this->hasMany(DetailPemesanan::class);
    }
}