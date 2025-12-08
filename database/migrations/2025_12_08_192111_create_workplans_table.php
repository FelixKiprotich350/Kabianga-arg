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
        Schema::create('workplans', function (Blueprint $table) {
            $table->string('workplanid')->primary();
            $table->unsignedBigInteger('proposalidfk')->index('workplans_proposalidfk_foreign');
            $table->string('activity');
            $table->string('time');
            $table->string('input');
            $table->string('facilities');
            $table->string('bywhom');
            $table->string('outcome');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workplans');
    }
};
