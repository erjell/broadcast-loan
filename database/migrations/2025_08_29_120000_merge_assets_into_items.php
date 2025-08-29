<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('serial_number')->nullable()->after('details');
            $table->unsignedSmallInteger('procurement_year')->nullable()->after('serial_number');
            $table->enum('condition', ['baik', 'rusak_ringan', 'rusak_berat'])->default('baik')->after('procurement_year');
        });

        Schema::dropIfExists('assets');
    }

    public function down(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->string('code')->unique();
            $table->string('serial_number')->nullable();
            $table->unsignedSmallInteger('procurement_year')->nullable();
            $table->enum('condition', ['baik', 'rusak_ringan', 'rusak_berat'])->default('baik');
            $table->timestamps();
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['serial_number', 'procurement_year', 'condition']);
        });
    }
};
