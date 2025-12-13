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
        Schema::table('supervisionprogress', function (Blueprint $table) {
            $table->foreign(['researchidfk'])->references(['researchid'])->on('researchprojects')->onUpdate('no action')->onDelete('restrict');
            $table->foreign(['supervisorfk'])->references(['userid'])->on('users')->onUpdate('no action')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supervisionprogress', function (Blueprint $table) {
            $table->dropForeign('supervisionprogress_researchidfk_foreign');
            $table->dropForeign('supervisionprogress_supervisorfk_foreign');
        });
    }
};
