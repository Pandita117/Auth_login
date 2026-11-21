<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('producto')->unique();
            $table->text('descripcion');
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('restrict');
            $table->decimal('precio', 10, 2);
            $table->integer('existencias')->default(0);
            $table->enum('estado', ['Disponible', 'Agotado'])->default('Disponible');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};