<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\{Loan, LoanItem, Item, Partner, User};

class LoanDummySeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Rizal',
            'Farqie',
            'Daniel',
            'Dhia M',
            'Salmen K',
            'Naafa',
            'Nasrul',
            'Yuman',
            'Haidar',
            'Nuge',
            'Akbal',
        ];

        // Ensure users (petugas) exist for these names
        $users = collect();
        foreach ($names as $name) {
            $base = Str::slug($name, '.');
            $email = $base.'@example.com';
            // Guarantee uniqueness if already exists
            $suffix = 1;
            while (User::where('email', $email)->exists()) {
                $email = $base.$suffix.'@example.com';
                $suffix++;
            }
            $user = User::firstOrCreate(
                ['name' => $name],
                [
                    'email' => $email,
                    'password' => Hash::make('password'),
                ]
            );
            $users->push($user);
        }

        // Ensure partners (peminjam) exist for these names
        $partners = collect();
        foreach ($names as $name) {
            $partners->push(
                Partner::firstOrCreate(
                    ['name' => $name],
                    [
                        'unit' => 'Personal',
                        'phone' => '08'.str_pad((string)random_int(100000000, 999999999), 9, '0', STR_PAD_LEFT),
                    ]
                )
            );
        }

        // Need items to attach to loans
        $items = Item::inRandomOrder()->take(50)->get(); // sample pool
        if ($items->count() < 5) {
            // Try to broaden the pool if few items exist
            $items = Item::all();
        }
        if ($items->isEmpty()) {
            // No items available; we still can create loans without items
            $this->command?->warn('Tidak ada item ditemukan. Loan akan dibuat tanpa LoanItem.');
        }

        $statusOptions = [
            // Weighted choices: dipinjam (5), sebagian_kembali (3), selesai (2)
            'dipinjam','dipinjam','dipinjam','dipinjam','dipinjam',
            'sebagian_kembali','sebagian_kembali','sebagian_kembali',
            'selesai','selesai',
        ];

        // Create 50 loans
        for ($i = 1; $i <= 50; $i++) {
            $partner = $partners->random();
            $user    = $users->random();
            $status  = $statusOptions[array_rand($statusOptions)];

            $loanDate = now()
                ->subDays(random_int(0, 90))
                ->setTime(random_int(8, 18), random_int(0, 59), 0);

            $purposes = [
                'Peliputan', 'Siaran Live', 'Produksi Konten', 'Dokumentasi', 'Workshop', 'Maintenance', 'Uji Coba'
            ];
            $purpose = $purposes[array_rand($purposes)].' '.Str::upper(Str::random(3));

            $loan = Loan::create([
                'partner_id' => $partner->id,
                'loan_date'  => $loanDate,
                'purpose'    => $purpose,
                'user_id'    => $user->id,
                'status'     => $status,
            ]);

            // Attach 1-4 items randomly if available
            if ($items->isNotEmpty()) {
                $take = random_int(1, min(4, max(1, $items->count())));
                $picked = $items->shuffle()->take($take);

                $loanItems = collect();
                foreach ($picked as $it) {
                    $loanItems->push(
                        LoanItem::firstOrCreate([
                            'loan_id' => $loan->id,
                            'item_id' => $it->id,
                        ])
                    );
                }

                // Set return conditions based on status for consistency
                if ($status === 'selesai') {
                    foreach ($loanItems as $li) {
                        $li->return_condition = collect(['baik','rusak_ringan','rusak_berat'])->random();
                        $li->return_notes = $li->return_condition === 'baik' ? null : 'Catatan: '.Str::title($li->return_condition);
                        $li->save();
                    }
                } elseif ($status === 'sebagian_kembali') {
                    // Mark some as returned, leave at least one still borrowed
                    $returnCount = max(1, (int) floor($loanItems->count() / 2));
                    foreach ($loanItems->shuffle()->take($returnCount) as $li) {
                        $li->return_condition = collect(['baik','rusak_ringan'])->random();
                        $li->return_notes = $li->return_condition === 'baik' ? null : 'Catatan: '.$li->return_condition;
                        $li->save();
                    }
                }
            }
        }
    }
}

