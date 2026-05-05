<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\OrangTua;

class PengaturanController extends Controller
{
    public function index()
    {
        $nakes = collect();
        $orangtuas = collect();
        if (Auth::user()->role === 'Admin') {
            $nakes = User::where('role', 'Nakes')->get();
            $orangtuas = User::where('role', 'Orangtua')->with('orangTua')->get();
        }
        return view('pengaturan.index', compact('nakes', 'orangtuas'));
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

    // ==================== ORANGTUA MANAGEMENT ====================

    public function storeOrangtua(Request $request)
    {
        if (Auth::user()->role !== 'Admin') abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'Orangtua',
            'email_verified_at' => now(), // Skip OTP karena dibuat oleh admin
        ]);

        OrangTua::create([
            'user_id' => $user->id,
            'nama_ortu' => $user->name,
            'email_ortu' => $user->email,
        ]);

        return back()->with('success', 'Akun Orangtua berhasil ditambahkan.');
    }

    public function updateOrangtua(Request $request, User $user)
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

        // Sync data ke tabel orang_tuas
        if ($user->orangTua) {
            $user->orangTua->update([
                'nama_ortu' => $request->name,
                'email_ortu' => $request->email,
            ]);
        }

        return back()->with('success', 'Akun Orangtua berhasil diperbarui.');
    }

    public function destroyOrangtua(User $user)
    {
        if (Auth::user()->role !== 'Admin') abort(403);
        if ($user->id === Auth::id()) abort(403, 'Tidak bisa menghapus akun sendiri');

        $user->delete();
        return back()->with('success', 'Akun Orangtua berhasil dihapus.');
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
