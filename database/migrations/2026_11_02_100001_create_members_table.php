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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nim', 30)->unique();
            $table->string('name');
            $table->string('generation', 10); // Angkatan misal 2021, 2022, dst.
            $table->enum('status', ['active', 'alumni', 'inactive'])->default('active');
            $table->string('role_in_org', 100)->nullable(); // Jabatan di HMIF misal: Ketua, Sekretaris, Anggota Divisi Medinfo, dsb.
            $table->text('bio')->nullable();
            $table->text('skills')->nullable(); // JSON / comma-separated tags keahlian
            $table->string('linkedin_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->string('avatar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
