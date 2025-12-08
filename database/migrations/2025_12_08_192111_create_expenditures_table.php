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
        Schema::create('expenditures', function (Blueprint $table) {
            $table->string('expenditureid')->primary();
            $table->unsignedBigInteger('proposalidfk')->index('expenditures_proposalidfk_foreign');
            $table->string('item');
            $table->unsignedBigInteger('itemtypeid')->nullable()->index('expenditures_itemtypeid_foreign');
            $table->integer('quantity');
            $table->decimal('unitprice');
            $table->decimal('total', 15);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenditures');
    }
};
