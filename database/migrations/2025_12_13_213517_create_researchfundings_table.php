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
        Schema::create('researchfundings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('researchidfk')->index('researchfundings_researchidfk_foreign');
            $table->char('createdby', 36)->index('researchfundings_createdby_foreign');
            $table->double('amount', null, 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('researchfundings');
    }
};
