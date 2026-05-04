<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_kpsps', function (Blueprint $table) {
            $table->id();
            $table->integer('umur_bulan');
            $table->string('sub_kategori')->nullable();
            $table->text('pertanyaan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_kpsps');
    }
};
