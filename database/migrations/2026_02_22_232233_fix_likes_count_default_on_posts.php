<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('posts', function (Blueprint $table) {
        // On s'assure que la colonne a 0 par défaut et n'est pas NULL
        $table->integer('likes_count')->default(0)->change();
    });
    
    // On met à jour les articles existants qui sont à NULL
    DB::table('posts')->whereNull('likes_count')->update(['likes_count' => 0]);
}
};
