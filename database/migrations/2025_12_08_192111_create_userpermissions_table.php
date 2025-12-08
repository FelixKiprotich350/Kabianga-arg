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
        Schema::create('userpermissions', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->string('useridfk')->index('userpermissions_useridfk_foreign');
            $table->string('permissionidfk')->index('userpermissions_permissionidfk_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('userpermissions');
    }
};
