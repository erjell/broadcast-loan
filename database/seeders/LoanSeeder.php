<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Models\{Loan, LoanItem, Item, Partner, User};

class LoanSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if (! $user) return; // membutuhkan user untuk relasi petugas

        $partners = Partner::take(3)->get();
        if ($partners->isEmpty()) return;

        $items = Item::take(8)->get();
        if ($items->count() < 3) return;

        // Buat 3 transaksi peminjaman dengan status berbeda
        $datasets = [
            [
                'partner' => $partners[0],
                'purpose' => 'Peliputan Acara A',
                'date'    => now()->subDays(7),
                'status'  => 'dipinjam',
                'item_idx'=> [0,1,2],
            ],
            [
                'partner' => $partners[1],
                'purpose' => 'Siaran Live B',
                'date'    => now()->subDays(3),
                'status'  => 'sebagian_kembali',
                'item_idx'=> [3,4],
            ],
            [
                'partner' => $partners[2],
                'purpose' => 'Produksi Konten C',
                'date'    => now()->subDay(),
                'status'  => 'selesai',
                'item_idx'=> [5,6,7],
            ],
        ];

        foreach ($datasets as $data) {
            $loan = Loan::create([
                'partner_id' => $data['partner']->id,
                'loan_date'  => $data['date'],
                'purpose'    => $data['purpose'],
                'user_id'    => $user->id,
                'status'     => $data['status'],
            ]);

            foreach ($data['item_idx'] as $idx) {
                $it = $items[$idx] ?? null;
                if (! $it) continue;
                LoanItem::firstOrCreate([
                    'loan_id' => $loan->id,
                    'item_id' => $it->id,
                ]);
            }
        }
    }
}

