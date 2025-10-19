<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if (!Schema::hasColumn('items', 'merk')) {
                $table->string('merk')->nullable()->after('name');
            }
            if (!Schema::hasColumn('items', 'tipe')) {
                $table->string('tipe')->nullable()->after('merk');
            }
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if (Schema::hasColumn('items', 'tipe')) {
                $table->dropColumn('tipe');
            }
            if (Schema::hasColumn('items', 'merk')) {
                $table->dropColumn('merk');
            }
        });
    }
};

