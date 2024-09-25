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
        Schema::table('equipment_monitorings', function (Blueprint $table) {
            // Drop the foreign key if exists
            $table->dropForeign(['facility_id']);
            $table->dropColumn('facility_id'); // If you want to remove the column entirely
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipment_monitorings', function (Blueprint $table) {
            //
        });
    }
};
