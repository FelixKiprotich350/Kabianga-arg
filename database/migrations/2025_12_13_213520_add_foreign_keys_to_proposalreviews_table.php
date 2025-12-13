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
        Schema::table('proposalreviews', function (Blueprint $table) {
            $table->foreign(['proposalid'])->references(['proposalid'])->on('proposals')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['reviewerid'])->references(['userid'])->on('users')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proposalreviews', function (Blueprint $table) {
            $table->dropForeign('proposalreviews_proposalid_foreign');
            $table->dropForeign('proposalreviews_reviewerid_foreign');
        });
    }
};
