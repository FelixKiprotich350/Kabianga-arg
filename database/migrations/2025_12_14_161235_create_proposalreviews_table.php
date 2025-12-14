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
        Schema::create('proposalreviews', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->unsignedBigInteger('proposalid')->index('proposalreviews_proposalid_foreign');
            $table->string('subject');
            $table->text('reviewcomment');
            $table->char('reviewerid', 36)->index('proposalreviews_reviewerid_foreign');
            $table->enum('status', ['pending', 'addressed'])->default('pending');
            $table->timestamp('addresstime')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposalreviews');
    }
};
