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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('leave_id')->references('id')->on('leaves')->onDelete('cascade');
            $table->date('date');
            $table->date('end_date');
            $table->integer('total_days');
            $table->char('condition',1);
            $table->text('information');
            $table->char('status',1)->default(0);
            $table->text('photo')->nullable();
            $table->time('jam_in_pengajuan')->nullable();
            $table->time('jam_out_pengajuan')->nullable();
            $table->date('tgl_koreksi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
