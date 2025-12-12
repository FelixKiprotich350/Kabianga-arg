<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('innovation_teams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proposal_id');
            $table->string('name');
            $table->string('contacts');
            $table->string('role');
            $table->timestamps();

            $table->foreign('proposal_id')->references('proposalid')->on('proposals')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('innovation_teams');
    }
};