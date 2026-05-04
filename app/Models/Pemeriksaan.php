<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemeriksaan extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function bayi() { return $this->belongsTo(Bayi::class); }
    public function nakes() { return $this->belongsTo(User::class, 'nakes_id'); }
    public function kpsp_jawabans() { return $this->hasMany(KpspJawaban::class); }
    public function tdd_jawabans() { return $this->hasMany(TddJawaban::class); }
}
