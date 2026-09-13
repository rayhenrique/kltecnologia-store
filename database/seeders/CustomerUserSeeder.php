<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()->firstOrNew(['email' => 'cliente@kltecnologia.test']);

        $user->forceFill([
            'name' => 'Cliente KL',
            'password' => Hash::make('password'),
            'role' => UserRole::Customer,
            'email_verified_at' => now(),
        ])->save();
    }
}
