<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('posts', 'likes')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->unsignedBigInteger('likes')->default(0);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('posts', 'likes')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->dropColumn('likes');
            });
        }
    }
};
