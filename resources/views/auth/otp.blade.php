@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-3xl shadow-xl border border-slate-100">
        <div>
            <h2 class="mt-2 text-center text-3xl font-extrabold text-slate-800">
                Verifikasi OTP
            </h2>
            <p class="mt-2 text-center text-sm text-slate-500">
                Masukkan kode OTP yang telah dikirim ke email Anda.
            </p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ route('otp.verify') }}" method="POST">
            @csrf
            <input type="hidden" name="email" value="{{ request('email') }}">

            <div class="space-y-4">
                <div>
                    <label for="otp" class="block text-sm font-medium text-slate-700 mb-1">Kode OTP</label>
                    <input id="otp" name="otp" type="text" required class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-400 text-slate-900 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary focus:z-10 sm:text-sm transition-all" placeholder="Masukkan 6 digit OTP">
                    @error('otp')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white bg-primary hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary shadow-md hover:shadow-lg transition-all duration-200">
                    Verifikasi
                </button>
            </div>
        </form>

        <form class="mt-4" action="{{ route('otp.resend') }}" method="POST">
            @csrf
            <input type="hidden" name="email" value="{{ request('email') }}">
            <button type="submit" class="w-full text-center text-sm text-primary hover:text-blue-600 transition font-medium">
                Kirim Ulang Kode OTP
            </button>
        </form>
    </div>
</div>
@endsection
