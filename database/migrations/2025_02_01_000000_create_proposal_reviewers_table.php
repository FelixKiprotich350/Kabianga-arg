<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('proposal_reviewers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proposal_id');
            $table->uuid('reviewer_id');
            $table->timestamp('assigned_at')->useCurrent();
            $table->uuid('assigned_by');
            $table->timestamps();

            $table->foreign('proposal_id')->references('proposalid')->on('proposals')->onDelete('cascade');
            $table->foreign('reviewer_id')->references('userid')->on('users')->onDelete('cascade');
            $table->foreign('assigned_by')->references('userid')->on('users')->onDelete('restrict');
            
            $table->unique(['proposal_id', 'reviewer_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('proposal_reviewers');
    }
};
