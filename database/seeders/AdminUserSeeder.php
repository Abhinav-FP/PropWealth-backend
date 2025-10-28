<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('slug', 'admin')->first();

        // Check if the admin role was found
        if (!$adminRole) {
            $this->command->error('Admin role not found. Please run php artisan db:seed --class=RoleSeeder first.');
            return;
        }

        // Create the admin user if they don't already exist
        User::firstOrCreate(
            [
                'email' => 'info@propwealth.com.au'
            ],
            [
                'name' => 'Admin User',
                'password' => Hash::make('12345678'),
                'role_id' => $adminRole->id,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin user created successfully!');
    }
}
