<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        User::create([
            'name' => 'NPTF Admin',
            'email' => 'admin@nptf.gov.ng',
            'password' => Hash::make('password123'), // Change this in production!
            'email_verified_at' => now(),
        ]);

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@nptf.gov.ng');
        $this->command->info('Password: password123');
        $this->command->warn('IMPORTANT: Change the password after first login!');
    }
}
