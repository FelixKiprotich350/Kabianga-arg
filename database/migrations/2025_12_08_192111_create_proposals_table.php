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
            $table->unsignedInteger('grantnofk')->index('proposals_grantnofk_foreign');
            $table->char('departmentidfk', 36)->index('proposals_departmentidfk_foreign');
            $table->char('useridfk', 36)->index('proposals_useridfk_foreign');
            $table->string('pfnofk')->index('proposals_pfnofk_foreign');
            $table->integer('themefk')->index('proposals_themefk_foreign');
            $table->enum('submittedstatus', ['PENDING', 'SUBMITTED'])->default('PENDING');
            $table->enum('receivedstatus', ['PENDING', 'RECEIVED'])->default('PENDING');
            $table->boolean('allowediting')->default(true);
            $table->enum('approvalstatus', ['DRAFT', 'PENDING', 'APPROVED', 'REJECTED'])->nullable()->default('PENDING');
            $table->string('researchtitle')->nullable();
            $table->date('commencingdate')->nullable();
            $table->date('terminationdate')->nullable();
            $table->text('objectives')->nullable();
            $table->text('hypothesis')->nullable();
            $table->text('significance')->nullable();
            $table->text('ethicals')->nullable();
            $table->text('expoutput')->nullable();
            $table->text('socio_impact')->nullable();
            $table->text('res_findings')->nullable();
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
