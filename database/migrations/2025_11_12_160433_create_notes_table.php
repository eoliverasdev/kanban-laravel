<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            // board_id (Clave Foránea a boards)
            $table->foreignId('board_id')->constrained()->onDelete('cascade');

            // Campos de la nota
            $table->string('title', 255);
            $table->text('description')->nullable();

            // status con valores limitados (enum)
            $table->enum('status', ['todo', 'in_progress', 'review', 'done'])->default('todo');

            // position (para ordenar las notas dentro del tablero)
            $table->unsignedInteger('position')->default(0); 

            // created_at y updated_at
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
