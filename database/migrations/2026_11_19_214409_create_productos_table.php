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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('producto')->unique();
            //eso es para que  Si una categoría tiene productos asignados, no podrás borrar la categoría hasta que reasignes o elimines esos productos. lo busque en gugel
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('restrict');
            $table->decimal('precio',10,2);
            $table->integer('cantidad')->default(0);
            $table->enum('estado', ['Disponible','Agotado'])->default('Disponible');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
