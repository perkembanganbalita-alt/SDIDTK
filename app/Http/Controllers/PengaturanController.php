<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PengaturanController extends Controller
{
    public function index()
    {
        $nakes = collect();
        if (Auth::user()->role === 'Admin') {
            $nakes = \App\Models\User::where('role', 'Nakes')->get();
        }
        return view('pengaturan.index', compact('nakes'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->with('error', 'Password saat ini salah.');
        }

        Auth::user()->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }

    public function storeNakes(Request $request)
    {
        if (Auth::user()->role !== 'Admin') abort(403);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6'
        ]);

        \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'Nakes',
            'email_verified_at' => now(),
        ]);

        return back()->with('success', 'Akun Nakes berhasil ditambahkan.');
    }

    public function updateNakes(Request $request, \App\Models\User $user)
    {
        if (Auth::user()->role !== 'Admin') abort(403);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:6'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return back()->with('success', 'Akun Nakes berhasil diperbarui.');
    }

    public function destroyNakes(\App\Models\User $user)
    {
        if (Auth::user()->role !== 'Admin') abort(403);
        
        // Prevent deleting yourself if somehow the user is admin trying to delete admin
        if ($user->id === Auth::id()) abort(403, 'Tidak bisa menghapus akun sendiri');
        
        $user->delete();
        return back()->with('success', 'Akun Nakes berhasil dihapus.');
    }

    public function backupDatabase()
    {
        if (Auth::user()->role !== 'Admin') abort(403);
        
        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST', '127.0.0.1');
        
        $fileName = "backup-" . date('Y-m-d_H-i-s') . ".sql";
        $filePath = storage_path("app/" . $fileName);
        
        $command = "mysqldump --user=" . escapeshellarg($username) . " --password=" . escapeshellarg($password) . " --host=" . escapeshellarg($host) . " " . escapeshellarg($database) . " > " . escapeshellarg($filePath);
        
        if (empty($password)) {
            $command = "mysqldump --user=" . escapeshellarg($username) . " --host=" . escapeshellarg($host) . " " . escapeshellarg($database) . " > " . escapeshellarg($filePath);
        }
        
        exec($command, $output, $returnVar);
        
        if ($returnVar === 0 && file_exists($filePath)) {
            return response()->download($filePath)->deleteFileAfterSend(true);
        } else {
            return back()->with('error', 'Gagal membuat backup. Pastikan utility mysqldump terinstal di server.');
        }
    }
}
