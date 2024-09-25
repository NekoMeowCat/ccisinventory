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
            $table->string('monitored_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
