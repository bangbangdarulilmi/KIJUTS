<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('academic_repositories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('uploader_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->enum('type', ['bank_soal', 'modul_praktikum', 'source_code', 'catatan_kuliah'])->default('bank_soal');
            $table->string('course_name', 150);
            $table->unsignedTinyInteger('semester');
            $table->string('academic_year', 20)->nullable();
            $table->enum('exam_type', ['uts', 'uas', 'kuis', 'tugas', 'other'])->nullable();
            $table->string('file_path');
            $table->string('file_size', 50)->nullable();
            $table->string('file_extension', 20)->nullable();
            $table->unsignedInteger('download_count')->default(0);
            $table->text('description')->nullable();
            $table->boolean('is_verified')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_repositories');
    }
};
