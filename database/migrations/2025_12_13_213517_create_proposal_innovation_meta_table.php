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
        Schema::create('proposal_innovation_meta', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->unsignedBigInteger('proposal_id')->index('proposal_innovation_meta_proposal_id_foreign');
            $table->text('gap');
            $table->text('solution');
            $table->text('targetcustomers');
            $table->text('valueproposition');
            $table->text('competitors');
            $table->text('attraction');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposal_innovation_meta');
    }
};
