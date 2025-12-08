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
        Schema::create('notifiableusers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('notificationfk', 36)->index('notifiableusers_notificationfk_foreign');
            $table->char('useridfk', 36)->index('notifiableusers_useridfk_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifiableusers');
    }
};
