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
        Schema::table('borrow_lists', function (Blueprint $table) {
            $table->date('date')->nullable();
            $table->string('purpose')->nullable();
            $table->string('date_and_time_of_use')->nullable();
            $table->string('college_department_office')->nullable();
            // $table->string('request_form')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrow_lists', function (Blueprint $table) {
            //
        });
    }
};
