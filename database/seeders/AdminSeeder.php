<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => 'admin@mytodoapp.tinnovations.com.ng',
                'phone' => '08026627552',
                'role' => 'admin',
                'password' => Hash::make('12345678'),
                'is_active' => true,
                'username' => 'superadmin',
                'gender' => 'female',
                'dob' => '2025-05-01',
                'status' => 'active',
                'email_verified_at' => now(),
            ],
        ];

        foreach($admins as $admin) {
            User::create($admin);
        }
    }
}
