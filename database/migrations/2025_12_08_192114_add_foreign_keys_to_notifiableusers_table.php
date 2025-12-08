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
        Schema::table('notifiableusers', function (Blueprint $table) {
            $table->foreign(['notificationfk'])->references(['typeuuid'])->on('notificationtypes')->onUpdate('no action')->onDelete('restrict');
            $table->foreign(['useridfk'])->references(['userid'])->on('users')->onUpdate('no action')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifiableusers', function (Blueprint $table) {
            $table->dropForeign('notifiableusers_notificationfk_foreign');
            $table->dropForeign('notifiableusers_useridfk_foreign');
        });
    }
};
