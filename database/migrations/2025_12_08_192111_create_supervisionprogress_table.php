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
        Schema::create('supervisionprogress', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('researchidfk')->index('supervisionprogress_researchidfk_foreign');
            $table->char('supervisorfk', 36)->index('supervisionprogress_supervisorfk_foreign');
            $table->text('report');
            $table->text('remark');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supervisionprogress');
    }
};
