<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->text('gap')->nullable();
            $table->text('solution')->nullable();
            $table->text('targetcustomers')->nullable();
            $table->text('valueproposition')->nullable();
            $table->text('competitors')->nullable();
            $table->text('attraction')->nullable();
        });
    }

    public function down()
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->dropColumn(['gap', 'solution', 'targetcustomers', 'valueproposition', 'competitors', 'attraction']);
        });
    }
};