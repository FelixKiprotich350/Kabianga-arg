<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->dropForeign(['pfnofk']);
            $table->dropColumn('pfnofk');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->string('pfno')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->string('pfnofk')->nullable();
            $table->foreign('pfnofk')->references('pfno')->on('users');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->string('pfno')->unique()->change();
        });
    }
};