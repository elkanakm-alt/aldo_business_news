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
        Schema::table('users', function (Blueprint $table) {

            // Vérifie si la colonne n'existe pas déjà (sécurité)
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')
                      ->default('user')
                      ->after('email'); // position propre dans la table
            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Supprime proprement la colonne au rollback
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }

        });
    }
};