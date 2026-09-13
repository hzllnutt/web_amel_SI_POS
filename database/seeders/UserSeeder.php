<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $kasirRole = Role::where('name', 'kasir')->first();
        $pimpinanRole = Role::where('name', 'pimpinan')->first();

        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'role_id' => $adminRole->id,
                'name' => 'Administrator',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'kasir@gmail.com'],
            [
                'role_id' => $kasirRole->id,
                'name' => 'Budi Kasir',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'pimpinan@gmail.com'],
            [
                'role_id' => $pimpinanRole->id,
                'name' => 'Bambang (Pimpinan)',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
            ]
        );

        // Alias for testing
        User::updateOrCreate(
            ['email' => 'admin@elscoffee.test'],
            [
                'role_id' => $adminRole->id,
                'name' => 'Administrator Els',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'kasir@elscoffee.test'],
            [
                'role_id' => $kasirRole->id,
                'name' => 'Budi Kasir Els',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
            ]
        );
    }
}
