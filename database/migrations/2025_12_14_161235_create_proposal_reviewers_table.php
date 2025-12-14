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
        Schema::create('proposal_reviewers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('proposal_id');
            $table->char('reviewer_id', 36)->index('proposal_reviewers_reviewer_id_foreign');
            $table->timestamp('assigned_at')->useCurrent();
            $table->char('assigned_by', 36)->index('proposal_reviewers_assigned_by_foreign');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['proposal_id', 'reviewer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposal_reviewers');
    }
};
