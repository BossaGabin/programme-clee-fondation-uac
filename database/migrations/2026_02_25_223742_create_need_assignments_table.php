<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('need_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidat_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('coach_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('interview_id')->constrained('interviews')->onDelete('cascade');
            $table->enum('type', [
                'stage',
                'insertion_emploi',
                'formation',
                'auto_emploi'
            ]);
            $table->text('description')->nullable();
            $table->string('duration')->nullable();      // ex: "3 mois"
            $table->date('program_start_date')->nullable();
            $table->date('program_end_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('need_assignments');
    }
};