@extends('layouts.app')

@section('content')
@if(Auth::user()->role === 'Orangtua')
<!-- Orangtua View -->
<div class="max-w-6xl mx-auto py-8" x-data="{ 
    showModal: {{ $errors->any() && !old('_method') ? 'true' : 'false' }},
    showEditModal: {{ $errors->hasBag('update') || (old('_method') == 'PUT' && $errors->any()) ? 'true' : 'false' }},
    showDeleteModal: false,
    editData: { 
        id: '{{ old('id', '') }}', 
        nama: '{{ addslashes(old('nama_bayi', '')) }}', 
        tgl_lahir: '{{ old('tgl_lahir', '') }}', 
        jenis_kelamin: '{{ old('jenis_kelamin', '') }}' 
    },
    deleteId: ''
}">
    <div class="flex flex-col md:flex-row justify-between md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Data Anak</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola data balita Anda</p>
        </div>
        <button @click="showModal = true" class="bg-success hover:bg-success/90 text-white text-sm font-medium py-2.5 px-6 rounded-xl shadow-sm transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Anak
        </button>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-success/10 border border-success/20 text-success px-4 py-3 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-slate-100 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="py-4 px-6">Nama Balita</th>
                        <th class="py-4 px-6">Tanggal Lahir</th>
                        <th class="py-4 px-6">Umur</th>
                        <th class="py-4 px-6">Jenis Kelamin</th>
                        <th class="py-4 px-6">Status Terakhir</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($bayis as $bayi)
                        @php
                            $tglLahir = \Carbon\Carbon::parse($bayi->tgl_lahir);
                            $diff = $tglLahir->diff(\Carbon\Carbon::now());
                            $umurBulan = ($diff->y * 12) + $diff->m;
                            if ($diff->d >= 16) {
                                $umurBulan += 1;
                            }
                            
                            $pemeriksaan = \App\Models\Pemeriksaan::where('bayi_id', $bayi->id)->whereNotNull('hasil_kpsp')->latest()->first();
                            $status = $pemeriksaan ? $pemeriksaan->hasil_kpsp : null;
                        @endphp
                        <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-success/10 text-success flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                    <span class="font-medium text-slate-800">{{ $bayi->nama_bayi }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-slate-600">{{ $tglLahir->format('Y-m-d') }}</td>
                            <td class="py-4 px-6 text-slate-600">{{ floor($umurBulan / 12) > 0 ? floor($umurBulan / 12) . ' Tahun ' : '' }}{{ $umurBulan % 12 > 0 ? ($umurBulan % 12) . ' Bulan' : '' }}{{ $umurBulan == 0 ? '0 Bulan' : '' }}</td>
                            <td class="py-4 px-6 text-slate-600">{{ $bayi->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td class="py-4 px-6">
                                @if($status && strpos($status, 'Sesuai') !== false)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-success/10 text-success">Sesuai Umur</span>
                                @elseif($status && strpos($status, 'Meragukan') !== false)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-warning/10 text-warning">Meragukan</span>
                                @elseif($status && strpos($status, 'Penyimpangan') !== false)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-danger/10 text-danger">Penyimpangan</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-500">Belum Skrining</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-2">

                                    <button @click="editData = { id: '{{ $bayi->id }}', nama: '{{ addslashes($bayi->nama_bayi) }}', tgl_lahir: '{{ $tglLahir->format('Y-m-d') }}', jenis_kelamin: '{{ $bayi->jenis_kelamin }}' }; showEditModal = true" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors text-xs font-bold" title="Edit">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        Edit
                                    </button>
                                    <button @click="deleteId = '{{ $bayi->id }}'; showDeleteModal = true" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-danger/10 text-danger hover:bg-danger/20 transition-colors text-xs font-bold" title="Hapus">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-500 text-sm border-t border-slate-50">
                                Belum ada data balita yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Anak -->
    <div x-show="showModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 z-50" style="display: none;">
        <div @click.away="showModal = false" class="bg-white rounded-3xl shadow-xl w-full max-w-md overflow-hidden"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
                <h2 class="text-lg font-bold text-slate-800">Tambah Data Anak</h2>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form action="{{ route('bayi.store') }}" method="POST" class="p-6">
                @csrf
                
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Anak</label>
                        <input type="text" name="nama_bayi" value="{{ old('nama_bayi') }}" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-success/20 focus:border-success transition-all text-sm placeholder-slate-400" placeholder="Masukkan nama anak">
                        @error('nama_bayi')<span class="text-xs text-danger mt-1 block">{{ $message }}</span>@enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Lahir</label>
                        <div class="relative">
                            <input type="date" name="tgl_lahir" value="{{ old('tgl_lahir') }}" required min="{{ \Carbon\Carbon::now()->subMonths(60)->format('Y-m-d') }}" max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-success/20 focus:border-success transition-all text-sm text-slate-700">
                        </div>
                        @error('tgl_lahir')<span class="text-xs text-danger mt-1 block">{{ $message }}</span>@enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jenis Kelamin</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="jenis_kelamin" value="L" class="peer sr-only" {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }} required>
                                <div class="text-center px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-medium text-slate-600 peer-checked:border-success peer-checked:bg-success/5 peer-checked:text-success transition-all">
                                    Laki-laki
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="jenis_kelamin" value="P" class="peer sr-only" {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }} required>
                                <div class="text-center px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-medium text-slate-600 peer-checked:border-success peer-checked:bg-success/5 peer-checked:text-success transition-all">
                                    Perempuan
                                </div>
                            </label>
                        </div>
                        @error('jenis_kelamin')<span class="text-xs text-danger mt-1 block">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <button type="button" @click="showModal = false" class="flex-1 text-center px-4 py-2.5 border border-slate-200 rounded-xl text-slate-600 font-bold text-sm hover:bg-slate-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-success rounded-xl text-white font-bold text-sm hover:bg-success/90 transition-colors shadow-sm">
                        Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Anak -->
    <div x-show="showEditModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 z-50" style="display: none;">
        <div @click.away="showEditModal = false" class="bg-white rounded-3xl shadow-xl w-full max-w-md overflow-hidden"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
                <h2 class="text-lg font-bold text-slate-800">Edit Data Anak</h2>
                <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form :action="'{{ url('bayi') }}/' + editData.id" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" x-model="editData.id">
                
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Anak</label>
                        <input type="text" name="nama_bayi" x-model="editData.nama" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-success/20 focus:border-success transition-all text-sm placeholder-slate-400">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Lahir</label>
                        <div class="relative">
                            <input type="date" name="tgl_lahir" x-model="editData.tgl_lahir" required min="{{ \Carbon\Carbon::now()->subMonths(60)->format('Y-m-d') }}" max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-success/20 focus:border-success transition-all text-sm text-slate-700">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jenis Kelamin</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="jenis_kelamin" value="L" x-model="editData.jenis_kelamin" class="peer sr-only" required>
                                <div class="text-center px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-medium text-slate-600 peer-checked:border-success peer-checked:bg-success/5 peer-checked:text-success transition-all">
                                    Laki-laki
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="jenis_kelamin" value="P" x-model="editData.jenis_kelamin" class="peer sr-only" required>
                                <div class="text-center px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-medium text-slate-600 peer-checked:border-success peer-checked:bg-success/5 peer-checked:text-success transition-all">
                                    Perempuan
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <button type="button" @click="showEditModal = false" class="flex-1 text-center px-4 py-2.5 border border-slate-200 rounded-xl text-slate-600 font-bold text-sm hover:bg-slate-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-success rounded-xl text-white font-bold text-sm hover:bg-success/90 transition-colors shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Hapus Anak -->
    <div x-show="showDeleteModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 z-50" style="display: none;">
        <div @click.away="showDeleteModal = false" class="bg-white rounded-3xl shadow-xl w-full max-w-sm overflow-hidden text-center p-6"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            
            <div class="w-12 h-12 rounded-full bg-danger/10 text-danger flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </div>
            
            <h2 class="text-lg font-bold text-slate-800 mb-2">Hapus Data Anak?</h2>
            <p class="text-sm text-slate-500 mb-8">Data yang dihapus tidak dapat dikembalikan.</p>
            
            <form :action="'{{ url('bayi') }}/' + deleteId" method="POST" class="flex gap-3">
                @csrf
                @method('DELETE')
                <button type="button" @click="showDeleteModal = false" class="flex-1 py-2.5 border border-slate-200 rounded-xl text-slate-600 font-bold text-sm hover:bg-slate-50 transition-colors">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2.5 bg-danger rounded-xl text-white font-bold text-sm hover:bg-danger/90 transition-colors shadow-sm">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@else
<!-- Admin/Nakes View -->
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row justify-between md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Data Anak</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola data anak yang terdaftar di Posyandu</p>
        </div>
        <div>
            <a href="{{ route('bayi.export') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 text-sm font-semibold rounded-xl text-slate-700 bg-white hover:bg-slate-50 transition-colors shadow-sm">
                <svg class="w-4 h-4 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export Data
            </a>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-slate-800">{{ $totalAnak ?? 0 }}</h3>
                <p class="text-xs text-slate-500 font-medium">Total Anak</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-slate-800">{{ $lakiLakiCount ?? 0 }}</h3>
                <p class="text-xs text-slate-500 font-medium">Laki-laki</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-pink-50 text-pink-500 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-slate-800">{{ $perempuanCount ?? 0 }}</h3>
                <p class="text-xs text-slate-500 font-medium">Perempuan</p>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden" x-data="{ search: '', gender: 'all' }">
        <div class="p-4 border-b border-slate-100 flex flex-col md:flex-row gap-4 items-center">
            <div class="relative w-full md:max-w-xs">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input x-model="search" type="text" class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-primary focus:border-primary bg-slate-50 placeholder-slate-400" placeholder="Cari nama anak atau orang tua...">
            </div>
            <select x-model="gender" class="w-full md:w-auto px-4 py-2 border border-slate-200 rounded-lg text-sm bg-slate-50 focus:ring-primary focus:border-primary text-slate-600">
                <option value="all">Semua JK</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>
            <div class="md:ml-auto text-sm text-slate-500">
                <span x-text="$root.querySelectorAll('tbody tr:not([style*=\'display: none\'])').length"></span> data
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-slate-100 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="py-4 px-6">No</th>
                        <th class="py-4 px-6">Nama Orang Tua</th>
                        <th class="py-4 px-6">Nama Anak</th>
                        <th class="py-4 px-6">JK</th>
                        <th class="py-4 px-6">Tanggal Lahir</th>
                        <th class="py-4 px-6">Umur</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @foreach($bayis as $index => $bayi)
                        @php
                            $tglLahir = \Carbon\Carbon::parse($bayi->tgl_lahir);
                            $diff = $tglLahir->diff(\Carbon\Carbon::now());
                            $umurBulan = ($diff->y * 12) + $diff->m;
                            if ($diff->d >= 16) {
                                $umurBulan += 1;
                            }
                        @endphp
                        <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors"
                            x-show="(gender === 'all' || '{{ $bayi->jenis_kelamin }}' === gender) && 
                                    ('{{ strtolower($bayi->nama_bayi) }}'.includes(search.toLowerCase()) || '{{ strtolower($bayi->orangTua->nama_ortu ?? '') }}'.includes(search.toLowerCase()))">
                            <td class="py-4 px-6 text-slate-500">{{ $index + 1 }}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-[10px]">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    </div>
                                    <span class="text-slate-700">{{ $bayi->orangTua->nama_ortu ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 font-medium text-slate-800">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full {{ $bayi->jenis_kelamin == 'L' ? 'bg-blue-400' : 'bg-pink-400' }}"></div>
                                    {{ $bayi->nama_bayi }}
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                @if($bayi->jenis_kelamin == 'L')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-600">Laki-laki</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-pink-50 text-pink-600">Perempuan</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-slate-600">{{ $tglLahir->translatedFormat('d M Y') }}</td>
                            <td class="py-4 px-6 font-medium text-success">{{ floor($umurBulan / 12) > 0 ? floor($umurBulan / 12) . ' Th ' : '' }}{{ $umurBulan % 12 > 0 ? ($umurBulan % 12) . ' Bln' : '' }}{{ $umurBulan == 0 ? '0 Bln' : '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection
