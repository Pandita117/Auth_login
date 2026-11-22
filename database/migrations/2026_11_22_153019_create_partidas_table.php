<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partidas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jugador_x_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('jugador_o_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->json('tablero');
            $table->enum('turno', ['X', 'O'])->default('X');
            $table->enum('estado', ['Esperando', 'En curso', 'Finalizada', 'Cancelada'])->default('Esperando');
            $table->string('resultado')->nullable();
            $table->foreignId('ganador_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partidas');
    }
};