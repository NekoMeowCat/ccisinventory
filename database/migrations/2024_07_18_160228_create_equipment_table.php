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
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('unit_id');
            $table->string('brand_name');
            $table->string('item_number');
            $table->string('property_number');
            $table->string('control_number');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('status');
            $table->string('date_acquired');
            $table->string('supplier');
            $table->string('quantity');
            $table->string('specification');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('facility_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
