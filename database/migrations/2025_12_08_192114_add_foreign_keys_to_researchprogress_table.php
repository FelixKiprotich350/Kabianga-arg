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
        Schema::table('researchprogress', function (Blueprint $table) {
            $table->foreign(['reportedbyfk'])->references(['userid'])->on('users')->onUpdate('no action')->onDelete('restrict');
            $table->foreign(['researchidfk'])->references(['researchid'])->on('researchprojects')->onUpdate('no action')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('researchprogress', function (Blueprint $table) {
            $table->dropForeign('researchprogress_reportedbyfk_foreign');
            $table->dropForeign('researchprogress_researchidfk_foreign');
        });
    }
};
