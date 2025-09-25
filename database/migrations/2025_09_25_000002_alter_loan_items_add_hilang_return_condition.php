<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tambahkan opsi 'hilang' pada enum return_condition
        DB::statement("ALTER TABLE loan_items MODIFY return_condition ENUM('baik','rusak_ringan','rusak_berat','hilang') NULL");
    }

    public function down(): void
    {
        // Kembalikan enum ke set semula (tanpa 'hilang')
        DB::statement("ALTER TABLE loan_items MODIFY return_condition ENUM('baik','rusak_ringan','rusak_berat') NULL");
    }
};

