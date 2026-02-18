<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mutations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('mutation_date');

            // Data Lama (Original)
            $table->foreignId('old_branch_id')->nullable();
            $table->foreignId('old_position_id')->nullable();

            // Data Baru (Target)
            $table->foreignId('new_branch_id')->nullable();
            $table->foreignId('new_position_id')->nullable();

            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mtations');
    }
};
