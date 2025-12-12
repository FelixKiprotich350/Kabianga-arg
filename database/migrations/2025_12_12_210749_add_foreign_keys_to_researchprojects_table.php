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
        Schema::table('researchprojects', function (Blueprint $table) {
            $table->foreign(['fundingfinyearfk'])->references(['id'])->on('finyears')->onUpdate('no action')->onDelete('restrict');
            $table->foreign(['proposalidfk'])->references(['proposalid'])->on('proposals')->onUpdate('no action')->onDelete('restrict');
            $table->foreign(['supervisorfk'])->references(['userid'])->on('users')->onUpdate('no action')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('researchprojects', function (Blueprint $table) {
            $table->dropForeign('researchprojects_fundingfinyearfk_foreign');
            $table->dropForeign('researchprojects_proposalidfk_foreign');
            $table->dropForeign('researchprojects_supervisorfk_foreign');
        });
    }
};
