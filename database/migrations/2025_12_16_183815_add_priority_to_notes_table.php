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
            // Afegim el camp 'priority'. Utilitzem ENUM per restringir els valors
            // i definim 'baix' com a valor per defecte.
            $table->enum('priority', ['baix', 'intermig', 'alt'])
                  ->default('baix')
                  ->after('responsible_id'); // O el camp que vulguis com a referència
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            // Mètode per eliminar el camp si fem rollback
            $table->dropColumn('priority');
        });
    }
};