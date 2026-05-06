@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Pengaturan Akun</h1>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-success/10 border border-success/20 text-success px-4 py-3 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 md:p-10">
            <h2 class="text-xl font-bold text-slate-800 mb-1">Pengaturan Akun</h2>
            <p class="text-sm text-slate-500 mb-10">Kelola informasi profil Anda</p>
            
            <form action="{{ route('orangtua.profile.update') }}" method="POST">
                @csrf
                

                <div class="max-w-2xl mx-auto space-y-6">
                    <!-- Nama Lengkap -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full pl-11 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-success/20 focus:border-success transition-all text-sm text-slate-700 placeholder-slate-400">
                        </div>
                        @error('name')<span class="text-xs text-danger mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full pl-11 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-success/20 focus:border-success transition-all text-sm text-slate-700 placeholder-slate-400">
                        </div>
                        @error('email')<span class="text-xs text-danger mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <!-- Nomor HP -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nomor HP</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </div>
                            <input type="text" name="nomor_hp" value="{{ old('nomor_hp', $user->nomor_hp) }}" class="w-full pl-11 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-success/20 focus:border-success transition-all text-sm text-slate-700 placeholder-slate-400" placeholder="Masukkan nomor HP">
                        </div>
                        @error('nomor_hp')<span class="text-xs text-danger mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <!-- Alamat -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Alamat</label>
                        <div class="relative">
                            <div class="absolute top-3 left-0 pl-4 flex pointer-events-none">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <textarea name="alamat" rows="2" class="w-full pl-11 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-success/20 focus:border-success transition-all text-sm text-slate-700 placeholder-slate-400" placeholder="Masukkan alamat lengkap">{{ old('alamat', $user->alamat) }}</textarea>
                        </div>
                        @error('alamat')<span class="text-xs text-danger mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <div class="pt-6 mt-6 border-t border-slate-100">
                        <h3 class="text-sm font-semibold text-slate-800 mb-6">Ubah Password</h3>
                        
                        <div class="space-y-6">
                            <!-- Password Baru -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Password Baru</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    </div>
                                    <input type="password" name="password" class="w-full pl-11 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-success/20 focus:border-success transition-all text-sm text-slate-700 placeholder-slate-400" placeholder="Kosongkan jika tidak ingin mengubah">
                                </div>
                                @error('password')<span class="text-xs text-danger mt-1 block">{{ $message }}</span>@enderror
                            </div>

                            <!-- Konfirmasi Password -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Konfirmasi Password</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    </div>
                                    <input type="password" name="password_confirmation" class="w-full pl-11 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-success/20 focus:border-success transition-all text-sm text-slate-700 placeholder-slate-400" placeholder="Ulangi password baru">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 mt-6">
                        <button type="submit" class="bg-success hover:bg-success/90 text-white font-bold py-3 px-8 rounded-xl shadow-sm transition-all text-sm">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
