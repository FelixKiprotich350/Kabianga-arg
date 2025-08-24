<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('proposals', function (Blueprint $table) {
            // Update submittedstatus from boolean to enum
            $table->enum('submittedstatus', ['PENDING', 'SUBMITTED'])->default('PENDING')->change();
            
            // Add receivedstatus enum field if it doesn't exist
            if (!Schema::hasColumn('proposals', 'receivedstatus')) {
                $table->enum('receivedstatus', ['PENDING', 'RECEIVED'])->default('PENDING');
            } else {
                $table->enum('receivedstatus', ['PENDING', 'RECEIVED'])->default('PENDING')->change();
            }
            
            // Update approvalstatus to use consistent enum values
            $table->enum('approvalstatus', ['DRAFT', 'PENDING', 'APPROVED', 'REJECTED'])->default('PENDING')->change();
            
            // Ensure allowediting is boolean
            if (!Schema::hasColumn('proposals', 'allowediting')) {
                $table->boolean('allowediting')->default(true);
            } else {
                $table->boolean('allowediting')->default(true)->change();
            }
        });
    }

    public function down()
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->boolean('submittedstatus')->default(false)->change();
            $table->boolean('receivedstatus')->default(false)->change();
            $table->string('approvalstatus')->default('Pending')->change();
            $table->boolean('allowediting')->default(true)->change();
        });
    }
};