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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // kode transaksi, ex: LOAN-2025-0001
            $table->foreignId('partner_id')->constrained()->cascadeOnDelete();
            $table->dateTime('loan_date');
            $table->string('purpose');        // keperluan
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // petugas
            $table->enum('status',['dipinjam','sebagian_kembali','selesai'])->default('dipinjam');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
