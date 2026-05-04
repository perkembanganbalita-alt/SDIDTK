<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemeriksaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bayi_id')->constrained('bayis')->cascadeOnDelete();
            $table->foreignId('nakes_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('tgl_pemeriksaan');
            $table->integer('umur_saat_periksa_bulan');
            $table->string('hasil_kpsp')->nullable(); // Sesuai (S), Meragukan (M), Penyimpangan (P)
            $table->string('hasil_tdd')->nullable(); // Normal, Curiga
            $table->integer('skor_kpsp')->nullable();
            $table->integer('skor_tdd')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemeriksaans');
    }
};
