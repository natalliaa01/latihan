<?php
// database/migrations/xxxx_xx_xx_create_kursuses_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kursuses', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kursus');
            $table->text('deskripsi')->nullable();
            $table->integer('durasi'); // dalam jam
            $table->foreignId('instruktur_id')->constrained('instrukturs')->onDelete('cascade');
            $table->decimal('biaya', 10, 2);
            $table->enum('status', ['aktif', 'tidak_aktif', 'draft'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kursuses');
    }
};