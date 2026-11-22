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
        Schema::create('partidas', function (Blueprint $table) {
            $table->id();
            $table->foreign('jugador_x_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('jugador_o_id')->constrained('users')->onDelete('casacade');
            $table->json('tablero');
            $table->enum('estado', ['Esperando','En curso', 'Finalizada','Cancelada'])->default('Esperand0');
            $table->string('resultado')->nullable();
            $table->foreignId('ganador_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partidas');
    }
};
