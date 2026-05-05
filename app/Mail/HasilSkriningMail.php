<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Pemeriksaan;
use Carbon\Carbon;

class HasilSkriningMail extends Mailable
{
    use Queueable, SerializesModels;

    public $jenis;
    public $namaOrtu;
    public $namaBayi;
    public $tanggalPemeriksaan;
    public $hasilSkrining;
    public $skorSkrining;
    public $umurSkrining;
    public $namaNakes;
    private $pdfData;
    private $pdfFilename;

    /**
     * Create a new message instance.
     */
    public function __construct(Pemeriksaan $pemeriksaan, string $jenis, $pdfData)
    {
        $this->jenis = $jenis;
        $this->namaOrtu = $pemeriksaan->bayi->orangTua->nama_ortu;
        $this->namaBayi = $pemeriksaan->bayi->nama_bayi;
        $this->tanggalPemeriksaan = Carbon::parse($pemeriksaan->tgl_pemeriksaan)->format('d F Y');
        $this->umurSkrining = $pemeriksaan->umur_saat_periksa_bulan;
        $this->namaNakes = optional($pemeriksaan->nakes)->name ?? 'Petugas Posyandu';
        $this->pdfData = $pdfData;

        if ($jenis === 'kpsp') {
            $this->hasilSkrining = $pemeriksaan->hasil_kpsp;
            $this->skorSkrining = $pemeriksaan->skor_kpsp;
        } else {
            $this->hasilSkrining = $pemeriksaan->hasil_tdd;
            $this->skorSkrining = $pemeriksaan->skor_tdd;
        }

        $this->pdfFilename = "Hasil_Skrining_" . strtoupper($jenis) . "_{$pemeriksaan->bayi->nama_bayi}.pdf";
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Hasil Skrining ' . strtoupper($this->jenis) . ' Ananda ' . $this->namaBayi . ' — PediatriCare')
                    ->view('emails.hasil-skrining')
                    ->attachData($this->pdfData, $this->pdfFilename, [
                        'mime' => 'application/pdf',
                    ]);
    }
}
