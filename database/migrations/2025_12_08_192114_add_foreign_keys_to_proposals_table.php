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
        Schema::table('proposals', function (Blueprint $table) {
            $table->foreign(['approvedrejectedbywhofk'])->references(['userid'])->on('users')->onUpdate('no action')->onDelete('restrict');
            $table->foreign(['departmentidfk'])->references(['depid'])->on('departments')->onUpdate('no action')->onDelete('restrict');
            $table->foreign(['grantnofk'])->references(['grantid'])->on('grants')->onUpdate('no action')->onDelete('restrict');
            $table->foreign(['pfnofk'])->references(['pfno'])->on('users')->onUpdate('no action')->onDelete('restrict');
            $table->foreign(['themefk'])->references(['themeid'])->on('researchthemes')->onUpdate('no action')->onDelete('restrict');
            $table->foreign(['useridfk'])->references(['userid'])->on('users')->onUpdate('no action')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->dropForeign('proposals_approvedrejectedbywhofk_foreign');
            $table->dropForeign('proposals_departmentidfk_foreign');
            $table->dropForeign('proposals_grantnofk_foreign');
            $table->dropForeign('proposals_pfnofk_foreign');
            $table->dropForeign('proposals_themefk_foreign');
            $table->dropForeign('proposals_useridfk_foreign');
        });
    }
};
