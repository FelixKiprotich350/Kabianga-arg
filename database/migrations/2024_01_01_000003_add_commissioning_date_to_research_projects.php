<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('researchprojects', function (Blueprint $table) {
            $table->date('commissioningdate')->nullable()->after('projectstatus');
        });
    }

    public function down()
    {
        Schema::table('researchprojects', function (Blueprint $table) {
            $table->dropColumn('commissioningdate');
        });
    }
};