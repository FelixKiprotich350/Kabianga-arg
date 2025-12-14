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
        Schema::create('proposals', function (Blueprint $table) {
            $table->bigIncrements('proposalid');
            $table->string('proposalcode')->unique();
            $table->enum('proposaltype', ['research', 'innovation'])->default('research');
            $table->unsignedInteger('grantnofk')->index('proposals_grantnofk_foreign');
            $table->char('departmentidfk', 36)->index('proposals_departmentidfk_foreign');
            $table->char('useridfk', 36)->index('proposals_useridfk_foreign');
            $table->integer('themefk')->index('proposals_themefk_foreign');
            $table->enum('submittedstatus', ['PENDING', 'SUBMITTED'])->default('PENDING');
            $table->enum('receivedstatus', ['PENDING', 'RECEIVED'])->default('PENDING');
            $table->boolean('allowediting')->default(true);
            $table->enum('approvalstatus', ['DRAFT', 'PENDING', 'APPROVED', 'REJECTED'])->nullable()->default('PENDING');
            $table->string('proposaltitle')->nullable();
            $table->date('commencingdate')->nullable();
            $table->date('terminationdate')->nullable();
            $table->text('comment')->nullable();
            $table->char('approvedrejectedbywhofk', 36)->nullable()->index('proposals_approvedrejectedbywhofk_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
