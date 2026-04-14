<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OwnerUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'owner@kos.com'],
            [
                'name' => 'Pemilik Kos',
                'password' => Hash::make('password123'),
            ]
        );
    }
}
