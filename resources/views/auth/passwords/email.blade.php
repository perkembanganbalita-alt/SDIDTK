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
                    <svg class="w-32 h-32 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                </div>
            </div>
            
            <h2 class="text-3xl font-bold mb-4 leading-tight">Lupa Kata Sandi?</h2>
            <p class="text-green-50 mb-10 text-sm leading-relaxed">
                Jangan khawatir. Masukkan email yang terdaftar, dan kami akan mengirimkan instruksi untuk mereset kata sandi Anda.
            </p>
        </div>

        <div class="mt-auto text-xs text-green-100/70 z-10">
            &copy; {{ date('Y') }} Posyandu Digital. Semua hak dilindungi.
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
                    Reset Password
                </h2>
                <p class="text-slate-500 text-sm">Masukkan email untuk menerima link reset password.</p>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-[#F2FDF5] border border-[#4CA861]/30 text-[#2D8D4A] px-4 py-3 rounded-xl flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <form class="space-y-5" action="{{ route('password.email') }}" method="POST">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">Email Anda</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required class="appearance-none rounded-lg relative block w-full pl-4 pr-4 py-3 border border-slate-200 placeholder-slate-400 text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#4CA861] focus:border-[#4CA861] sm:text-sm transition-all shadow-sm" placeholder="Masukkan email terdaftar" value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center items-center gap-2 py-3 px-4 border border-transparent text-sm font-semibold rounded-lg text-white bg-[#4CA861] hover:bg-[#3d8c4e] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4CA861] transition-all shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        Kirim Link Reset
                    </button>
                </div>
                
                <div class="text-center pt-4 text-sm text-slate-500">
                    Ingat password Anda? 
                    <a href="{{ route('login') }}" class="font-semibold text-[#4CA861] hover:text-[#3a834c] transition">Kembali ke Login</a>
                </div>
            </form>
        </div>
        
        <div class="text-center text-xs text-slate-400 mt-8 lg:hidden">
            &copy; {{ date('Y') }} Posyandu Digital. Semua hak dilindungi.
        </div>
    </div>
</div>
@endsection
