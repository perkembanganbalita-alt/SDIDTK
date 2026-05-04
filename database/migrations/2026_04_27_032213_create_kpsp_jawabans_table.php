<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kpsp_jawabans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemeriksaan_id')->constrained('pemeriksaans')->cascadeOnDelete();
            $table->text('pertanyaan');
            $table->string('sub_kategori')->nullable();
            $table->enum('jawaban', ['Ya', 'Tidak']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kpsp_jawabans');
    }
};
