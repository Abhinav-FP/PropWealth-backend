<?php

namespace Database\Seeders;

use App\Models\UserDownloadLimit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserDownloadLimitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (UserDownloadLimit::count() === 0) {
            UserDownloadLimit::create([
                'daily_limit' => 5,
                'lifetime_limit' => 20,
            ]);
            $this->command->info('Default user download limit seeded successfully!');
        } else {
            $this->command->info('User download limit already exists. Skipping seeding.');
        }
    }
}
