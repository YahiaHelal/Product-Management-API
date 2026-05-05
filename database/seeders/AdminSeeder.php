<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'password', // Auto-hashed
            'email_verified_at' => now(),
        ]);

        $this->command->info('Admin user created: admin@example.com / password');
    }
}
