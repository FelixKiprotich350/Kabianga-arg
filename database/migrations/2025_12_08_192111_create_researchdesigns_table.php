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
        Schema::create('researchdesigns', function (Blueprint $table) {
            $table->string('designid')->primary();
            $table->unsignedBigInteger('proposalidfk')->index('researchdesigns_proposalidfk_foreign');
            $table->string('summary');
            $table->text('indicators');
            $table->string('verification');
            $table->text('assumptions');
            $table->string('goal');
            $table->string('purpose');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('researchdesigns');
    }
};
