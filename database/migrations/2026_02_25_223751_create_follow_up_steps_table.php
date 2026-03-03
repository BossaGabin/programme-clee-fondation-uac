<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('follow_up_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidat_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('coach_id')->constrained('users')->onDelete('cascade');
            $table->string('title');                    // ex: "Rédaction du CV"
            $table->text('description')->nullable();    // détail de ce qui a été fait
            $table->enum('status', ['in_progress', 'completed'])->default('in_progress');
            $table->date('completed_date')->nullable(); // renseigné quand completed
            $table->text('result')->nullable();         // observation du coach
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('follow_up_steps');
    }
};