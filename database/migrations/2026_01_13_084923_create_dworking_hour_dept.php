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
        Schema::create('working_hour_dept_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('whd_id')->constrained('working_hour_dept')->onDelete('cascade');
            $table->string('days');
            $table->foreignId('workinghour_id')->constrained('working_hours')->onDelete('no action');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dworking_hour_dept');
    }
};
