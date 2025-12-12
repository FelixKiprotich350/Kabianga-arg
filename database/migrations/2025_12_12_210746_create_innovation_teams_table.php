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
        Schema::create('innovation_teams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('proposal_id')->index('innovation_teams_proposal_id_foreign');
            $table->string('name');
            $table->string('contacts');
            $table->string('role');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('innovation_teams');
    }
};
