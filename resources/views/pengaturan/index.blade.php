@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto" x-data="{ 
    activeTab: 'keamanan',
    showAddModal: false,
    showEditModal: false,
    showDeleteModal: false,
    editData: { id: '', name: '', email: '' },
    deleteId: ''
}">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Pengaturan</h1>
        <p class="text-sm text-slate-500 mt-1">Atur preferensi aplikasi sesuai kebutuhan Anda</p>
    </div>

    <!-- Tabs -->
    <div class="flex items-center gap-2 mb-6 border-b border-slate-100 pb-2">
        <button @click="activeTab = 'keamanan'" :class="activeTab === 'keamanan' ? 'bg-primary text-white' : 'text-slate-600 hover:bg-slate-50'" class="px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            Keamanan
        </button>
        @if(Auth::user()->role === 'Admin')
        <button @click="activeTab = 'nakes'" :class="activeTab === 'nakes' ? 'bg-primary text-white' : 'text-slate-600 hover:bg-slate-50'" class="px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            Manajemen Nakes
        </button>
        @endif
        @if(Auth::user()->role === 'Admin')
        <button @click="activeTab = 'backup'" :class="activeTab === 'backup' ? 'bg-primary text-white' : 'text-slate-600 hover:bg-slate-50'" class="px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
            Data & Backup
        </button>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-6 bg-success/10 border border-success/20 text-success px-4 py-3 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 bg-danger/10 border border-danger/20 text-danger px-4 py-3 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Content Keamanan -->
    <div x-show="activeTab === 'keamanan'" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-50">
            <h3 class="text-base font-bold text-slate-800">Keamanan Akun</h3>
            <p class="text-xs text-slate-500">Kelola password dan keamanan akun Anda</p>
        </div>
        
        <div class="p-6">
            <form action="{{ route('pengaturan.password') }}" method="POST" class="max-w-md space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Password Saat Ini</label>
                    <div class="relative">
                        <input type="password" name="current_password" required class="block w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:ring-primary focus:border-primary bg-slate-50" placeholder="Masukkan password saat ini">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Password Baru</label>
                    <div class="relative">
                        <input type="password" name="password" required class="block w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:ring-primary focus:border-primary bg-slate-50" placeholder="Minimal 6 karakter">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Konfirmasi Password Baru</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" required class="block w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:ring-primary focus:border-primary bg-slate-50" placeholder="Ulangi password baru">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-primary text-white rounded-xl text-sm font-bold shadow-sm hover:bg-primary/90 transition-colors">
                        Simpan Password Baru
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if(Auth::user()->role === 'Admin')
    <!-- Content Manajemen Nakes -->
    <div x-show="activeTab === 'nakes'" style="display: none;" class="space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-50 flex justify-between items-center">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Manajemen Nakes (Kader/Bidan)</h3>
                    <p class="text-xs text-slate-500">Kelola akun staf kesehatan yang bertugas</p>
                </div>
                <button @click="showAddModal = true" class="bg-primary hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Akun
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="py-3 px-6">Nama Lengkap</th>
                            <th class="py-3 px-6">Email</th>
                            <th class="py-3 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-50">
                        @forelse($nakes as $staff)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3 px-6 font-medium text-slate-800">{{ $staff->name }}</td>
                            <td class="py-3 px-6 text-slate-600">{{ $staff->email }}</td>
                            <td class="py-3 px-6 text-right">
                                <div class="flex justify-end gap-2">
                                    <button @click="editData = { id: '{{ $staff->id }}', name: '{{ addslashes($staff->name) }}', email: '{{ addslashes($staff->email) }}' }; showEditModal = true" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <button @click="deleteId = '{{ $staff->id }}'; showDeleteModal = true" class="w-8 h-8 rounded-lg bg-danger/10 text-danger flex items-center justify-center hover:bg-danger/20 transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-6 text-center text-slate-500 text-sm">Belum ada data Nakes.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Nakes -->
    <div x-show="showAddModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 z-50" style="display: none;">
        <div @click.away="showAddModal = false" class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <h2 class="text-lg font-bold text-slate-800">Tambah Akun Nakes</h2>
                <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('pengaturan.nakes.store') }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                        <input type="email" name="email" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                        <input type="password" name="password" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-primary focus:border-primary" placeholder="Minimal 6 karakter">
                    </div>
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="button" @click="showAddModal = false" class="flex-1 py-2.5 border border-slate-200 rounded-xl text-slate-600 font-bold text-sm hover:bg-slate-50 transition-colors">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 bg-primary rounded-xl text-white font-bold text-sm hover:bg-primary/90 transition-colors shadow-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Nakes -->
    <div x-show="showEditModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 z-50" style="display: none;">
        <div @click.away="showEditModal = false" class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <h2 class="text-lg font-bold text-slate-800">Edit Akun Nakes</h2>
                <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form :action="'{{ url('pengaturan/nakes') }}/' + editData.id" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" x-model="editData.name" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                        <input type="email" name="email" x-model="editData.email" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Password Baru (Opsional)</label>
                        <input type="password" name="password" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-primary focus:border-primary" placeholder="Kosongkan jika tidak ingin diubah">
                    </div>
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="button" @click="showEditModal = false" class="flex-1 py-2.5 border border-slate-200 rounded-xl text-slate-600 font-bold text-sm hover:bg-slate-50 transition-colors">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 bg-primary rounded-xl text-white font-bold text-sm hover:bg-primary/90 transition-colors shadow-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Hapus Nakes -->
    <div x-show="showDeleteModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 z-50" style="display: none;">
        <div @click.away="showDeleteModal = false" class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden text-center p-6">
            <div class="w-12 h-12 rounded-full bg-danger/10 text-danger flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </div>
            <h2 class="text-lg font-bold text-slate-800 mb-2">Hapus Akun Nakes?</h2>
            <p class="text-sm text-slate-500 mb-8">Akun ini tidak akan bisa login lagi ke dalam sistem.</p>
            <form :action="'{{ url('pengaturan/nakes') }}/' + deleteId" method="POST" class="flex gap-3">
                @csrf
                @method('DELETE')
                <button type="button" @click="showDeleteModal = false" class="flex-1 py-2.5 border border-slate-200 rounded-xl text-slate-600 font-bold text-sm hover:bg-slate-50 transition-colors">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-danger rounded-xl text-white font-bold text-sm hover:bg-danger/90 transition-colors shadow-sm">Ya, Hapus</button>
            </form>
        </div>
    </div>
    @endif

    @if(Auth::user()->role === 'Admin')
    <!-- Content Data & Backup -->
    <div x-show="activeTab === 'backup'" style="display: none;" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-50">
            <h3 class="text-base font-bold text-slate-800">Backup Database</h3>
            <p class="text-xs text-slate-500">Amankan data sistem dengan melakukan backup secara berkala</p>
        </div>
        <div class="p-6 space-y-6">
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex gap-4">
                <div class="text-blue-500 mt-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-blue-900 mb-1">Informasi Backup</h4>
                    <p class="text-xs text-blue-700 leading-relaxed">
                        Fitur ini akan mengunduh seluruh data aplikasi (database MySQL) ke dalam format <code>.sql</code>. Sangat disarankan untuk melakukan backup setiap bulan atau sebelum melakukan pembaruan sistem yang besar.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('pengaturan.backup') }}" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white px-6 py-3 rounded-xl text-sm font-bold transition-colors shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Unduh Backup Database (.sql)
                </a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
