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
        Schema::create('publications', function (Blueprint $table) {
            $table->string('publicationid')->primary();
            $table->unsignedBigInteger('proposalidfk')->index('publications_proposalidfk_foreign');
            $table->string('authors');
            $table->string('year');
            $table->string('title');
            $table->string('researcharea');
            $table->string('publisher');
            $table->string('volume');
            $table->integer('pages');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
