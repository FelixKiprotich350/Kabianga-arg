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
        Schema::create('researchthemes', function (Blueprint $table) {
            $table->integer('themeid')->primary();
            $table->string('themename')->unique();
            $table->text('themedescription');
            $table->string('applicablestatus');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('researchthemes');
    }
};
