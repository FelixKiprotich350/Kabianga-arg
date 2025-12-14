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
        Schema::table('expenditures', function (Blueprint $table) {
            $table->foreign(['itemtypeid'])->references(['typeid'])->on('expendituretypes')->onUpdate('no action')->onDelete('restrict');
            $table->foreign(['proposalidfk'])->references(['proposalid'])->on('proposals')->onUpdate('no action')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenditures', function (Blueprint $table) {
            $table->dropForeign('expenditures_itemtypeid_foreign');
            $table->dropForeign('expenditures_proposalidfk_foreign');
        });
    }
};
