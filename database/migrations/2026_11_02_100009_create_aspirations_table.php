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
        Schema::create('aspirations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('sender_name', 100);
            $table->string('sender_email', 100);
            $table->string('subject', 200);
            $table->text('message');
            $table->enum('category', ['fasilitas', 'akademik', 'kegiatan_himpunan', 'kritik_saran', 'lainnya'])->default('kritik_saran');
            $table->boolean('is_public')->default(false);
            $table->enum('status', ['unread', 'read', 'responded'])->default('unread');
            $table->text('response')->nullable();
            $table->foreignId('responded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aspirations');
    }
};
