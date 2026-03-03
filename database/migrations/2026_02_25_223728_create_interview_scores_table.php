<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interview_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interview_id')->constrained('interviews')->onDelete('cascade');
            $table->foreignId('competence_id')->constrained('competences')->onDelete('cascade');
            $table->integer('note');  // 0 à 20
            $table->text('comment')->nullable();
            $table->timestamps();

            // Une seule note par compétence par entretien
            $table->unique(['interview_id', 'competence_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interview_scores');
    }
};