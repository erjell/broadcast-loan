<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin default
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );

        // Petugas tambahan
        User::firstOrCreate(
            ['email' => 'petugas@example.com'],
            [
                'name' => 'Petugas',
                'password' => Hash::make('password'),
            ]
        );

        // Accounts based on provided first names
        $fullNames = [
            'DWI YULIARSA',
            'BAHTIAR RIFAII',
            'JEFFRY C SANDYAGA',
            'BRIAN PURNOMO',
            'TRI WAHYUDIONO',
            'DUKE IMANUEL',
            'OCEAN FAT ILHAM',
            'DENY ARDIYAN',
            'YONGKY A SETYAWAN',
            'AHMAD FERRI F',
            'HASAN',
            'RIFQI RIZHANSYAH',
            'RIZAL A',
            'FARQIE',
            'DHIA MAULANA',
            'SALMAN',
            'DANIEL TUAHTARAS',
            'NAFA ADEVY PUTRI',
        ];

        foreach ($fullNames as $fullName) {
            $firstName = strtolower(strtok($fullName, ' '));
            $email = $firstName . '@be.id';
            User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => ucwords(strtolower($fullName)),
                    'password' => Hash::make('ngadmin123'),
                ]
            );
        }
    }
}
