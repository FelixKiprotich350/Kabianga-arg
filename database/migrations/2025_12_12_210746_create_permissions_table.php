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
        Schema::create('permissions', function (Blueprint $table) {
            $table->char('pid', 36)->primary();
            $table->string('menuname');
            $table->string('shortname')->unique();
            $table->string('path');
            $table->integer('priorityno');
            $table->integer('permissionlevel');
            $table->integer('targetrole');
            $table->boolean('issuperadminright')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
