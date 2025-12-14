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
        Schema::create('proposal_research_meta', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->unsignedBigInteger('proposal_id')->index('proposal_research_meta_proposal_id_foreign');
            $table->text('objectives')->nullable();
            $table->text('hypothesis')->nullable();
            $table->text('significance')->nullable();
            $table->text('ethicals')->nullable();
            $table->text('expoutput')->nullable();
            $table->text('socio_impact')->nullable();
            $table->text('res_findings')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposal_research_meta');
    }
};
