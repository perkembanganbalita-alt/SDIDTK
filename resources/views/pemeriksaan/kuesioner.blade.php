@extends('layouts.app')

@section('content')
@php
    // Combine kpsp and tdd for a seamless 1-by-1 question flow?
    // The image shows 10 questions (KPSP usually has 10 questions).
    // What about TDD? The images don't show TDD. We can either merge TDD at the end or handle it as extra questions.
    // Let's assume the UI in image is for KPSP. Let's make the wizard handle all questions (KPSP + TDD).
    $allQuestions = collect();
    if ($jenis === 'kpsp') {
        foreach($kpsp as $q) {
            $allQuestions->push((object)[
                'id' => 'kpsp_'.$q->id,
                'real_id' => $q->id,
                'type' => 'kpsp',
                'pertanyaan' => $q->pertanyaan,
                'kategori' => $q->sub_kategori
            ]);
        }
    } elseif ($jenis === 'tdd') {
        foreach($tdd as $q) {
            $allQuestions->push((object)[
                'id' => 'tdd_'.$q->id,
                'real_id' => $q->id,
                'type' => 'tdd',
                'pertanyaan' => $q->pertanyaan,
                'kategori' => 'Daya Dengar'
            ]);
        }
    }
    $totalQuestions = $allQuestions->count();
    
    // Anak Info
    $bayi = $pemeriksaan->bayi;
    $tglLahir = \Carbon\Carbon::parse($bayi->tgl_lahir);
    $diff = $tglLahir->diff(\Carbon\Carbon::now());
    $umurBulan = ($diff->y * 12) + $diff->m;
    if ($diff->d >= 16) $umurBulan += 1;
    $umurText = floor($umurBulan / 12) > 0 ? floor($umurBulan / 12) . ' Th ' : '';
    $umurText .= $umurBulan % 12 > 0 ? ($umurBulan % 12) . ' Bln' : '';
    if ($umurBulan == 0) $umurText = '0 Bln';
@endphp

<div class="max-w-7xl mx-auto" x-data="kuesionerWizard({{ $totalQuestions }})">
    <!-- Header -->
    <div class="mb-8 text-center md:text-left">
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Kuesioner Skrining {{ strtoupper($jenis) }}</h1>
        <p class="mt-1 text-sm text-slate-500">Jawab semua pertanyaan sesuai dengan kondisi anak saat ini</p>
    </div>

    <!-- Stepper -->
    <div class="flex items-center justify-start gap-2 sm:gap-4 mb-6 sm:mb-8 overflow-x-auto pb-2 -mx-4 px-4 sm:mx-0 sm:px-0">
        <div class="flex items-center gap-1.5 sm:gap-2 bg-success/10 text-success px-3 sm:px-4 py-2 rounded-full text-xs sm:text-sm font-bold whitespace-nowrap shrink-0">
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            Pilih Anak
        </div>
        <div class="w-6 sm:w-8 h-px bg-slate-200 shrink-0"></div>
        <div class="flex items-center gap-1.5 sm:gap-2 bg-success/10 text-success px-3 sm:px-4 py-2 rounded-full text-xs sm:text-sm font-bold shadow-sm ring-1 ring-success/20 whitespace-nowrap shrink-0">
            <span>2</span> Kuesioner {{ strtoupper($jenis) }}
        </div>
        <div class="w-6 sm:w-8 h-px bg-slate-200 shrink-0"></div>
        <div class="flex items-center gap-1.5 sm:gap-2 text-slate-400 px-3 sm:px-4 py-2 rounded-full text-xs sm:text-sm font-medium whitespace-nowrap shrink-0">
            <span>3</span> Hasil
        </div>
    </div>

    <form action="{{ route('pemeriksaan.submitKuesioner', ['jenis' => $jenis, 'pemeriksaan' => $pemeriksaan->id]) }}" method="POST" id="kuesionerForm" @submit.prevent="submitForm">
        @csrf
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Left Column: Kuesioner -->
            <div class="flex-grow max-w-3xl">
                <!-- Progress Box -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
                    <div class="flex justify-between items-end mb-4">
                        <span class="text-sm font-bold text-slate-700">Progress Pemeriksaan</span>
                        <span class="text-sm font-bold text-success" x-text="answeredCount + '/{{ $totalQuestions }} Pertanyaan'"></span>
                    </div>
                    <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden mb-4">
                        <div class="h-full bg-success transition-all duration-300" :style="'width: ' + ((answeredCount / {{ $totalQuestions }}) * 100) + '%'"></div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="i in {{ $totalQuestions }}" :key="i">
                            <div class="w-8 h-8 rounded flex items-center justify-center text-xs font-bold transition-colors"
                                 :class="{
                                     'bg-success text-white': answers[i] === 'Ya',
                                     'bg-danger text-white': answers[i] === 'Tidak',
                                     'bg-slate-100 text-slate-400': !answers[i],
                                     'ring-2 ring-primary ring-offset-2': currentStep === i
                                 }"
                                 @click="if(i <= maxStepReached) currentStep = i"
                                 style="cursor: pointer;">
                                <template x-if="answers[i] === 'Ya'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </template>
                                <template x-if="answers[i] === 'Tidak'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </template>
                                <template x-if="!answers[i]">
                                    <span x-text="i"></span>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Questions -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 sm:p-8 relative min-h-[250px] sm:min-h-[300px]">
                    @foreach($allQuestions as $index => $q)
                        <div x-show="currentStep === {{ $index + 1 }}" x-transition.opacity style="display: none;">
                            <div class="flex items-center gap-3 mb-6">
                                <span class="px-3 py-1 bg-success/10 text-success text-xs font-bold rounded-md">{{ $q->kategori }}</span>
                                <span class="text-sm text-slate-500 font-medium">Pertanyaan {{ $index + 1 }} dari {{ $totalQuestions }}</span>
                            </div>
                            
                            <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-slate-800 mb-6 sm:mb-8 leading-snug">
                                {{ $q->pertanyaan }}
                            </h2>

                            <!-- Hidden Radio inputs to store actual form data -->
                            <input type="radio" name="{{ $q->type }}[{{ $q->real_id }}]" value="Ya" class="hidden" x-model="answers[{{ $index + 1 }}]">
                            <input type="radio" name="{{ $q->type }}[{{ $q->real_id }}]" value="Tidak" class="hidden" x-model="answers[{{ $index + 1 }}]">

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <button type="button" @click="selectAnswer({{ $index + 1 }}, 'Ya')" 
                                        class="flex items-center justify-center gap-3 py-4 rounded-xl border-2 font-bold text-lg transition-all"
                                        :class="answers[{{ $index + 1 }}] === 'Ya' ? 'border-success bg-success/5 text-success' : 'border-slate-200 text-slate-600 hover:border-slate-300 hover:bg-slate-50'">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    Ya
                                </button>
                                <button type="button" @click="selectAnswer({{ $index + 1 }}, 'Tidak')"
                                        class="flex items-center justify-center gap-3 py-4 rounded-xl border-2 font-bold text-lg transition-all"
                                        :class="answers[{{ $index + 1 }}] === 'Tidak' ? 'border-danger bg-danger/5 text-danger' : 'border-slate-200 text-slate-600 hover:border-slate-300 hover:bg-slate-50'">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    Tidak
                                </button>
                            </div>
                        </div>
                    @endforeach

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between items-center mt-10 pt-6 border-t border-slate-100">
                        <button type="button" @click="prevStep()" class="flex items-center gap-2 px-6 py-3 border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-colors" :class="{'opacity-0 pointer-events-none': currentStep === 1}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Sebelumnya
                        </button>

                        <button type="button" @click="nextStepOrSubmit()" class="flex items-center gap-2 px-6 py-3 bg-success text-white font-bold rounded-xl hover:bg-success/90 shadow-sm transition-all" x-show="currentStep < {{ $totalQuestions }}">
                            Selanjutnya
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>

                        <button type="button" @click="showModal = true" class="flex items-center gap-2 px-6 py-3 bg-success text-white font-bold rounded-xl hover:bg-success/90 shadow-sm transition-all" x-show="currentStep === {{ $totalQuestions }} && answeredCount === {{ $totalQuestions }}" style="display: none;">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            Simpan Jawaban
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column: Info Anak -->
            <div class="w-full lg:w-80 shrink-0">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sticky top-6">
                    <h3 class="text-xs font-bold text-slate-400 tracking-wider mb-6">INFO ANAK</h3>
                    
                    <div class="flex flex-col items-center text-center mb-8">
                        <div class="w-20 h-20 rounded-full mb-3 flex items-center justify-center {{ $bayi->jenis_kelamin == 'L' ? 'bg-blue-50 text-blue-500' : 'bg-pink-50 text-pink-500' }}">
                            @if($bayi->jenis_kelamin == 'L')
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            @else
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            @endif
                        </div>
                        <h4 class="text-lg font-bold text-slate-800">{{ $bayi->nama_bayi }}</h4>
                        <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs font-bold {{ $bayi->jenis_kelamin == 'L' ? 'bg-blue-50 text-blue-600' : 'bg-pink-50 text-pink-600' }}">
                            {{ $bayi->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </span>
                    </div>

                    <div class="space-y-4 mb-8">
                        <div class="flex items-start gap-3 bg-slate-50 p-3 rounded-xl">
                            <div class="mt-0.5 text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-medium">Orang Tua</p>
                                <p class="text-sm font-bold text-slate-800">{{ $bayi->orangTua->nama_ortu ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 bg-slate-50 p-3 rounded-xl">
                            <div class="mt-0.5 text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-medium">Umur</p>
                                <p class="text-sm font-bold text-slate-800">{{ $umurText }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 bg-slate-50 p-3 rounded-xl">
                            <div class="mt-0.5 text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-medium">Tanggal Pemeriksaan</p>
                                <p class="text-sm font-bold text-slate-800">{{ \Carbon\Carbon::parse($pemeriksaan->tgl_pemeriksaan)->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3 pt-4 border-t border-slate-100">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500">Jawaban Ya</span>
                            <span class="font-bold text-success" x-text="countYa"></span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500">Jawaban Tidak</span>
                            <span class="font-bold text-danger" x-text="countTidak"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm" x-transition.opacity>
            <div class="bg-white rounded-3xl shadow-xl w-full max-w-md p-8 transform transition-all text-center" @click.away="showModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">
                <div class="w-16 h-16 rounded-full bg-success/10 text-success flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 mb-2">Simpan Jawaban?</h3>
                <p class="text-slate-500 mb-8">Anda telah menjawab <span x-text="answeredCount"></span> dari {{ $totalQuestions }} pertanyaan. Yakin ingin menyimpan dan melihat hasil?</p>
                
                <div class="flex gap-4 justify-center">
                    <button type="button" @click="showModal = false" class="px-6 py-3 border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-colors flex-1">
                        Kembali
                    </button>
                    <button type="submit" :disabled="isSubmitting" class="px-6 py-3 bg-success text-white font-bold rounded-xl hover:bg-success/90 shadow-sm transition-all flex-1 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        <svg x-show="isSubmitting" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="isSubmitting ? 'Menyimpan...' : 'Ya, Simpan'"></span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('kuesionerWizard', (total) => ({
        currentStep: 1,
        maxStepReached: 1,
        answers: {},
        showModal: false,
        isSubmitting: false,
        
        get answeredCount() {
            return Object.keys(this.answers).length;
        },
        
        get countYa() {
            return Object.values(this.answers).filter(v => v === 'Ya').length;
        },
        
        get countTidak() {
            return Object.values(this.answers).filter(v => v === 'Tidak').length;
        },

        selectAnswer(step, val) {
            this.answers[step] = val;
            if(step > this.maxStepReached) this.maxStepReached = step;
            
            // Auto advance
            setTimeout(() => {
                if(step < total) {
                    this.currentStep = step + 1;
                    if(this.currentStep > this.maxStepReached) this.maxStepReached = this.currentStep;
                }
            }, 300);
        },
        
        nextStepOrSubmit() {
            if(!this.answers[this.currentStep]) {
                alert('Silakan pilih jawaban terlebih dahulu.');
                return;
            }
            if(this.currentStep < total) {
                this.currentStep++;
                if(this.currentStep > this.maxStepReached) this.maxStepReached = this.currentStep;
            }
        },
        
        prevStep() {
            if(this.currentStep > 1) {
                this.currentStep--;
            }
        },

        submitForm() {
            if(this.answeredCount < total) {
                alert('Mohon jawab semua pertanyaan (' + this.answeredCount + '/' + total + ')');
                this.showModal = false;
                return;
            }
            this.isSubmitting = true;
            document.getElementById('kuesionerForm').submit();
        }
    }))
})
</script>
@endsection
