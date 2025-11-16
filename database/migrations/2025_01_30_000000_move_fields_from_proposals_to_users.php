<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add fields to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('highqualification')->nullable();
            $table->string('officenumber')->nullable();
            $table->string('faxnumber')->nullable();
        });

        // Remove fields from proposals table
        Schema::table('proposals', function (Blueprint $table) {
            $table->dropColumn(['highqualification', 'officephone', 'faxnumber']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Add fields back to proposals table
        Schema::table('proposals', function (Blueprint $table) {
            $table->string('highqualification');
            $table->string('officephone');
            $table->string('faxnumber');
        });

        // Remove fields from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['highqualification', 'officenumber', 'faxnumber']);
        });
    }
};