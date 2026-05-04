<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tdd_jawabans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemeriksaan_id')->constrained('pemeriksaans')->cascadeOnDelete();
            $table->text('pertanyaan');
            $table->enum('jawaban', ['Ya', 'Tidak']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tdd_jawabans');
    }
};
