<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            // Rename column purpose -> lokasi if exists
            if (Schema::hasColumn('loans', 'purpose')) {
                $table->renameColumn('purpose', 'lokasi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            if (Schema::hasColumn('loans', 'lokasi')) {
                $table->renameColumn('lokasi', 'purpose');
            }
        });
    }
};

