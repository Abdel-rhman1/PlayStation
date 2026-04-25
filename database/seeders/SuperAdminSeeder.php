<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'super@playstation.com'],
            [
                'name' => 'System Architect',
                'password' => Hash::make('password'),
                'tenant_id' => null,
            ]
        );
    }
}
