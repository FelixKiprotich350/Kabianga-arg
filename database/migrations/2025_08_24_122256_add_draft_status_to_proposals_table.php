<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Add DRAFT to the approvalstatus enum
        DB::statement("ALTER TABLE proposals MODIFY COLUMN approvalstatus ENUM('DRAFT', 'PENDING', 'APPROVED', 'REJECTED') DEFAULT 'PENDING'");
    }

    public function down()
    {
        // Remove DRAFT from the approvalstatus enum
        DB::statement("ALTER TABLE proposals MODIFY COLUMN approvalstatus ENUM('PENDING', 'APPROVED', 'REJECTED') DEFAULT 'PENDING'");
    }
};