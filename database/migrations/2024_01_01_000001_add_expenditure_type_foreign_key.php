<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Add new foreign key column
        Schema::table('expenditures', function (Blueprint $table) {
            $table->unsignedBigInteger('itemtypeid')->nullable()->after('itemtype');
        });

        // Update foreign key values based on existing itemtype strings
        DB::statement("
            UPDATE expenditures e 
            SET itemtypeid = (
                SELECT typeid 
                FROM expendituretypes et 
                WHERE et.typename = e.itemtype 
                LIMIT 1
            )
            WHERE e.itemtype IS NOT NULL
        ");

        // Add foreign key constraint
        Schema::table('expenditures', function (Blueprint $table) {
            $table->foreign('itemtypeid')->references('typeid')->on('expendituretypes')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::table('expenditures', function (Blueprint $table) {
            $table->dropForeign(['itemtypeid']);
            $table->dropColumn('itemtypeid');
        });
    }
};