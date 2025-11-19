<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('expenditures', function (Blueprint $table) {
            $table->dropColumn('itemtype');
        });
    }

    public function down()
    {
        Schema::table('expenditures', function (Blueprint $table) {
            $table->string('itemtype')->nullable()->after('item');
        });
    }
};