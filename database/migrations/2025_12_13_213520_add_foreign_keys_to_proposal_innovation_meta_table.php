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
        Schema::table('proposal_innovation_meta', function (Blueprint $table) {
            $table->foreign(['proposal_id'])->references(['proposalid'])->on('proposals')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proposal_innovation_meta', function (Blueprint $table) {
            $table->dropForeign('proposal_innovation_meta_proposal_id_foreign');
        });
    }
};
