<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('expendituretypes', function (Blueprint $table) {
            $table->id('typeid');
            $table->string('typename')->unique();
            $table->text('description');
            $table->boolean('isactive')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('expendituretypes');
    }
};