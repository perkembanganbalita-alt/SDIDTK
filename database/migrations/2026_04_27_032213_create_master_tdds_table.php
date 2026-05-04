<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_tdds', function (Blueprint $table) {
            $table->id();
            $table->integer('umur_min');
            $table->integer('umur_max');
            $table->text('pertanyaan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_tdds');
    }
};
