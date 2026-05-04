@extends('layouts.app')

@section('content')
<div class="min-h-screen flex w-full">
    <!-- Left Section -->
    <div class="hidden lg:flex lg:w-1/2 bg-[#2D8D4A] relative flex-col justify-between items-center p-12 text-white overflow-hidden">
        <!-- Logo -->
        <div class="absolute top-8 left-8 flex items-center gap-3 z-10">
            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center p-2">
                <svg viewBox="0 0 24 24" class="w-full h-full text-[#2D8D4A]" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                </svg>
            </div>
            <div>
                <h1 class="font-bold text-lg leading-tight">Posyandu Digital</h1>
                <p class="text-xs text-green-100">Sistem Tumbuh Kembang Anak</p>
            </div>
        </div>

        <!-- Illustration & Content -->
        <div class="mt-20 flex flex-col items-center max-w-md text-center z-10">
            <div class="w-64 h-64 bg-green-500/30 rounded-full flex items-center justify-center mb-8 relative">
                <div class="w-56 h-56 bg-green-400/40 rounded-full flex items-center justify-center relative shadow-xl">
                    <img src="{{ asset('images/baby_illustration.png') }}" alt="Baby Illustration" class="w-48 h-48 rounded-full object-cover shadow-2xl ring-4 ring-green-300/50">
                </div>
            </div>
            
            <h2 class="text-3xl font-bold mb-4 leading-tight">Pantau Tumbuh Kembang<br>Anak dengan Mudah</h2>
            <p class="text-green-50 mb-10 text-sm leading-relaxed">
                Sistem digital Posyandu untuk monitoring<br>pertumbuhan dan perkembangan anak secara<br>akurat dan efisien.
            </p>

            <div class="flex gap-8 text-center mt-4">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mb-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <p class="font-bold text-lg">1,240+</p>
                    <p class="text-xs text-green-100">Data Anak</p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mb-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </div>
                    <p class="font-bold text-lg">3,800+</p>
                    <p class="text-xs text-green-100">Pemeriksaan</p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mb-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <p class="font-bold text-lg">48</p>
                    <p class="text-xs text-green-100">Posyandu</p>
                </div>
            </div>
        </div>

        <div class="mt-auto text-xs text-green-100/70 z-10">
            &copy; 2026 Posyandu Digital. Semua hak dilindungi.
        </div>
        
        <!-- Abstract background shapes -->
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute top-20 -right-20 w-80 h-80 bg-white/5 rounded-full blur-3xl"></div>
    </div>

    <!-- Right Section -->
    <div class="w-full lg:w-1/2 flex flex-col justify-between p-8 sm:p-12 lg:p-16 xl:p-24 bg-white relative">
        <div class="max-w-md w-full mx-auto my-auto">
            <div class="mb-8">
                <h2 class="text-3xl font-extrabold text-slate-800 flex items-center gap-2 mb-2">
                    Selamat Datang <span class="text-2xl">👋</span>
                </h2>
                <p class="text-slate-500 text-sm">Masuk ke Sistem Tumbuh Kembang Anak Posyandu</p>
            </div>

            <form class="space-y-5" action="{{ route('login') }}" method="POST">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required class="appearance-none rounded-lg relative block w-full pl-10 px-4 py-3 border border-slate-200 placeholder-slate-400 text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#4CA861] focus:border-[#4CA861] focus:z-10 sm:text-sm transition-all shadow-sm" placeholder="Masukkan email Anda" value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
                    <div class="relative" x-data="{ show: false }">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input id="password" name="password" :type="show ? 'text' : 'password'" autocomplete="current-password" required class="appearance-none rounded-lg relative block w-full pl-10 pr-10 px-4 py-3 border border-slate-200 placeholder-slate-400 text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#4CA861] focus:border-[#4CA861] focus:z-10 sm:text-sm transition-all shadow-sm" placeholder="Masukkan password Anda">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                            <svg x-show="!show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg x-show="show" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-[#4CA861] focus:ring-[#4CA861] border-slate-300 rounded cursor-pointer transition">
                        <label for="remember_me" class="ml-2 block text-sm text-slate-600 cursor-pointer">
                            Ingat saya
                        </label>
                    </div>
                    <div class="text-sm">
                        <a href="#" class="font-medium text-[#4CA861] hover:text-[#3a834c] transition">Lupa Password?</a>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center items-center gap-2 py-3 px-4 border border-transparent text-sm font-semibold rounded-lg text-white bg-[#4CA861] hover:bg-[#3d8c4e] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4CA861] transition-all shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                        Masuk
                    </button>
                </div>
                
                <div class="text-center pt-2 text-sm text-slate-500">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="font-semibold text-[#4CA861] hover:text-[#3a834c] transition">Daftar</a>
                </div>
            </form>
            
            <div class="mt-12">
                <div class="bg-[#F2FDF5] border border-green-100 rounded-xl p-4 flex gap-4 items-center">
                    <div class="w-10 h-10 bg-green-100 text-[#4CA861] rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800">Akses Aman & Terenkripsi</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Data anak terlindungi dengan sistem keamanan tingkat tinggi</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center text-xs text-slate-400 mt-8 lg:hidden">
            &copy; 2026 Posyandu Digital. Semua hak dilindungi.
        </div>
    </div>
</div>

<style>
[x-cloak] { display: none !important; }
</style>
@endsection
