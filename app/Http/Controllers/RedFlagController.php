<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tahapan;

class RedFlagController extends Controller
{
    public function index()
    {
        $tahapans = Tahapan::whereNotNull('red_flags')->orderBy('umur_min')->get();
        
        $user = auth()->user();
        $bayis = collect();
        if ($user->role === 'Orangtua' && $user->orangTua) {
            $bayis = \App\Models\Bayi::where('orang_tua_id', $user->orangTua->id)->get();
        }

        return view('redflag.index', compact('tahapans', 'bayis'));
    }
}
