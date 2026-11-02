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
        Schema::create('coding_clinics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('category', 50)->default('bug_error'); // tugas_kuliah, proyek_mandiri, tugas_akhir, bug_error, general
            $table->string('programming_language', 50)->nullable();
            $table->longText('description');
            $table->longText('code_snippet')->nullable();
            $table->string('screenshot')->nullable();
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open');
            $table->foreignId('mentor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->longText('solution_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('coding_clinic_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained('coding_clinics')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->longText('reply');
            $table->longText('code_snippet')->nullable();
            $table->boolean('is_solution')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coding_clinic_replies');
        Schema::dropIfExists('coding_clinics');
    }
};
