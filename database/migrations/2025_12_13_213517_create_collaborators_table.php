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
        Schema::create('collaborators', function (Blueprint $table) {
            $table->string('collaboratorid')->primary();
            $table->unsignedBigInteger('proposalidfk')->index('collaborators_proposalidfk_foreign');
            $table->string('collaboratorname');
            $table->string('position');
            $table->string('institution');
            $table->string('researcharea');
            $table->string('experience');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collaborators');
    }
};
