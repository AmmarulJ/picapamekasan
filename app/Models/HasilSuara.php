<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilSuara extends Model
{
    use HasFactory;

    protected $fillable = ['tps', 'paslon1', 'paslon2', 'paslon3', 'Status', 'suara_tidak_sah', 'jumlah_kehadiran'];

    public function tps()
    {
        return $this->belongsTo(Tps::class, 'tps', 'id');
    }
    public function gambarBukti()
    {
        return $this->hasMany(GambarBukti::class);
    }
}
