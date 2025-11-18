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
        Schema::table('notes', function (Blueprint $table) {
            // Canviem el 'status' a un ENUM amb els valors permesos, que és el més segur.
            // La clau és usar CHANGE per modificar una columna existent.
            $table->enum('status', ['pending', 'in_progress', 'done'])
                  ->default('pending')
                  ->change();
            
            // Si la teva BD no suporta CHANGE, pots provar amb VARCHAR(20) si falla
            // $table->string('status', 20)->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations (per si desfem els canvis).
     */
    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            // Tornem al tipus de dades anterior (aquí suposarem un VARCHAR curt o ENUM simple)
            // Aquesta part és opcional, però bona pràctica.
            $table->string('status', 10)->default('pending')->change();
        });
    }
};