<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progression_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coach_assignment_id')->constrained('coach_assignments')->onDelete('cascade');

            // Scores mis à jour par le coach (null = pas modifié cette séance)
            $table->tinyInteger('bloc_a')->nullable(); // 0-20
            $table->tinyInteger('bloc_b')->nullable();
            $table->tinyInteger('bloc_c')->nullable();
            $table->tinyInteger('bloc_d')->nullable();
            $table->tinyInteger('bloc_e')->nullable();

            // Ce que le coach a observé cette séance
            $table->text('note_seance');

            $table->timestamps(); // created_at = date de la séance
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progression_updates');
    }
};