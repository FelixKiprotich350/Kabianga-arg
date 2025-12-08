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
        Schema::table('proposal_reviewers', function (Blueprint $table) {
            $table->foreign(['assigned_by'])->references(['userid'])->on('users')->onUpdate('no action')->onDelete('restrict');
            $table->foreign(['proposal_id'])->references(['proposalid'])->on('proposals')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['reviewer_id'])->references(['userid'])->on('users')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proposal_reviewers', function (Blueprint $table) {
            $table->dropForeign('proposal_reviewers_assigned_by_foreign');
            $table->dropForeign('proposal_reviewers_proposal_id_foreign');
            $table->dropForeign('proposal_reviewers_reviewer_id_foreign');
        });
    }
};
