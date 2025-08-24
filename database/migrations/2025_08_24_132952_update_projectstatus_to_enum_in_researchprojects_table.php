<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('researchprojects', function (Blueprint $table) {
            $table->enum('projectstatus', ['ACTIVE', 'PAUSED', 'CANCELLED', 'COMPLETED'])
                  ->default('ACTIVE')
                  ->change();
        });
    }

    public function down()
    {
        Schema::table('researchprojects', function (Blueprint $table) {
            $table->string('projectstatus')->default('active')->change();
        });
    }
};