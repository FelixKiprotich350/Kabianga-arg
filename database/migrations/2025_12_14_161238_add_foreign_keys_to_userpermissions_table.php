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
        Schema::table('userpermissions', function (Blueprint $table) {
            $table->foreign(['permissionidfk'])->references(['pid'])->on('permissions')->onUpdate('no action')->onDelete('restrict');
            $table->foreign(['useridfk'])->references(['userid'])->on('users')->onUpdate('no action')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('userpermissions', function (Blueprint $table) {
            $table->dropForeign('userpermissions_permissionidfk_foreign');
            $table->dropForeign('userpermissions_useridfk_foreign');
        });
    }
};
