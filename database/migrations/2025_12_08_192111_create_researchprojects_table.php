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
        Schema::create('researchprojects', function (Blueprint $table) {
            $table->bigIncrements('researchid');
            $table->string('researchnumber')->unique();
            $table->unsignedBigInteger('proposalidfk')->unique();
            $table->enum('projectstatus', ['ACTIVE', 'PAUSED', 'CANCELLED', 'COMPLETED'])->default('ACTIVE');
            $table->date('commissioningdate')->nullable();
            $table->boolean('ispaused')->default(false);
            $table->string('supervisorfk')->nullable()->index('researchprojects_supervisorfk_foreign');
            $table->unsignedBigInteger('fundingfinyearfk')->index('researchprojects_fundingfinyearfk_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('researchprojects');
    }
};
