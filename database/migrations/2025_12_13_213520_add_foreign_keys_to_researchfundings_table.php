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
        Schema::table('researchfundings', function (Blueprint $table) {
            $table->foreign(['createdby'])->references(['userid'])->on('users')->onUpdate('no action')->onDelete('restrict');
            $table->foreign(['researchidfk'])->references(['researchid'])->on('researchprojects')->onUpdate('no action')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('researchfundings', function (Blueprint $table) {
            $table->dropForeign('researchfundings_createdby_foreign');
            $table->dropForeign('researchfundings_researchidfk_foreign');
        });
    }
};
