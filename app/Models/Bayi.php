<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bayi extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function orangTua() { return $this->belongsTo(OrangTua::class); }
    public function pemeriksaans() { return $this->hasMany(Pemeriksaan::class); }
}
