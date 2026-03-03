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
        Schema::table('comments', function (Blueprint $table) {
            // 1. parent_id : Permet de savoir si un commentaire est une réponse à un autre
            // On le met en 'nullable' car les commentaires classiques n'ont pas de parent.
            $table->foreignId('parent_id')
                  ->after('user_id') 
                  ->nullable()
                  ->constrained('comments')
                  ->onDelete('cascade');

            // 2. approved : Permet de valider ou non un commentaire (modération)
            // Par défaut à 'false' (0) pour que tu doives les valider dans ton AdminHub.
            $table->boolean('approved')->default(false)->after('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            // On retire les colonnes si on annule la migration
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'approved']);
        });
    }
};