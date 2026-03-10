<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidat_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('date_of_birth')->nullable();              // 20%
            $table->enum('gender', ['homme', 'femme'])->nullable(); // 10%
            $table->string('address')->nullable();                  // 10%
            $table->string('niveau_etude')->nullable();             // 20%
            $table->string('domaine_formation')->nullable();        // 20%
            $table->integer('experience_years')->nullable();        // 10%
            $table->enum('situation_actuelle', [
                'en_emploi',
                'sans_emploi',
                'etudiant',
                'autre'
            ])->nullable();
            $table->string('situation_autre')->nullable();                                          // 10%
            $table->string('cv_path')->nullable();                  // optionnel, hors calcul
            $table->integer('profile_completion')->default(0);      // 0 à 100
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidat_profiles');
    }
};