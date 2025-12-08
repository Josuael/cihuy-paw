<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'username' => 'admin',
            'email' => 'admin@ug.com',
            'password' => Hash::make('password'),
            'full_name' => 'Administrator',
            'role' => 'admin',
        ]);

        User::create([
            'username' => 'staff',
            'email' => 'staff@ug.com',
            'password' => Hash::make('password'),
            'full_name' => 'Staff Kredit',
            'role' => 'staff',
        ]);

        User::create([
            'username' => 'ketua',
            'email' => 'ketua@ug.com',
            'password' => Hash::make('password'),
            'full_name' => 'Ketua Koperasi',
            'role' => 'ketua',
        ]);

        User::create([
            'username' => 'member',
            'email' => 'member@ug.com',
            'password' => Hash::make('password'),
            'full_name' => 'Member Testing',
            'role' => 'member',
        ]);
        
        $this->call(UserSeeder::class);
    }
}
