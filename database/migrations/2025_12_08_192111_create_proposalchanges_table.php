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
        Schema::create('proposalchanges', function (Blueprint $table) {
            $table->bigIncrements('changeid');
            $table->unsignedBigInteger('proposalidfk')->index('proposalchanges_proposalidfk_foreign');
            $table->text('triggerissue');
            $table->text('suggestedchange');
            $table->string('suggestedbyfk');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposalchanges');
    }
};
