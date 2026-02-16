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
        Schema::create('location', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama Titik (Misal: Lobby Utama, Proyek A, Ganti Shift)
            $table->string('latitude');
            $table->string('longitude');
            $table->integer('radius')->default(5); // Satuan Meter
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location');
    }
};
