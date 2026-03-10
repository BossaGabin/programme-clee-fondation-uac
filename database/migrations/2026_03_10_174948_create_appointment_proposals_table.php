<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointment_proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coach_assignment_id')->constrained('coach_assignments')->onDelete('cascade');

            // 3 horaires proposés
            $table->date('date_1');
            $table->time('heure_1');
            $table->date('date_2');
            $table->time('heure_2');
            $table->date('date_3');
            $table->time('heure_3');

            // Mode d'entretien — récupéré depuis la demande du candidat
            $table->enum('mode', ['presentiel', 'en_ligne']);

            // Détails présentiel
            $table->string('location')->nullable();

            // Détails en ligne — plateforme choisie par le candidat lors de sa demande
            $table->enum('plateforme_enligne', ['whatsapp', 'google_meet', 'appel_direct'])->nullable();
            $table->string('numero_whatsapp')->nullable();   // si whatsapp
            $table->string('numero_appel')->nullable();      // si appel direct
            $table->string('meeting_link')->nullable();      // si google meet — envoyé par le coach

            // Statut et choix
            $table->enum('status', ['pending', 'confirmed', 'expired'])->default('pending');
            $table->integer('choix_candidat')->nullable(); // 1, 2 ou 3

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_proposals');
    }
};