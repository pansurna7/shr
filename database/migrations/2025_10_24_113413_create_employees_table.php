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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            // Foreign Keys to essential tables
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Corrected table name (assuming 'positions' is the correct table)
            $table->foreignId('position_id')->constrained('positions')->onDelete('no action');
            $table->boolean('is_free_absent')->default(0)->after('position_id');

            // Adjusted to standard plural convention
            $table->foreignId('branch_id')->constrained('branches')->onDelete('no action');

            // Identification & Personal Data
            $table->string('nik')->unique();
            $table->string('nomor_ktp');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->date('date_of_birth');
            $table->string('gender')->nullable();
            $table->string('religion')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('jumlah_anak')->nullable();
            $table->string('education')->nullable();
            $table->text('address')->nullable();
            $table->string('mobile')->nullable();
            $table->string('avatar')->nullable();
            $table->text('face_descriptor')->nullable();

            // Financial & Employment Data
            // Explicitly defining precision for monetary values
            $table->decimal('gaji_pokok', 15, 2);

            // Often only the date is necessary for these fields
            $table->date('tanggal_diangkat');

            // Must be nullable for active employees
            $table->date('tanggal_keluar')->nullable();

            $table->string('nomor_rekening')->nullable();
            $table->string('rekening_atas_nama')->nullable();
            // Sisa jatah dari tahun sebelumnya
            $table->integer('kuota_tahun_lalu')->default(0);
            // Jatah murni tahun berjalan (misal 12 hari)
            $table->integer('kuota_tahun_ini')->default(12);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
