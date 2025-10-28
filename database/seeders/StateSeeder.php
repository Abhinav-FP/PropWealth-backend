<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel; // Import the Excel facade
use App\Imports\StatesImport;
use App\Models\State;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the path to your JSON data file
        $filePath = database_path('seeders/data/states.json'); // Changed to states.json

        // Ensure the JSON file exists before attempting to read it
        if (!File::exists($filePath)) {
            $this->command->error("State seed data JSON file not found at: {$filePath}");
            return;
        }

        // Decode the JSON data into a PHP array
        $statesData = json_decode(File::get($filePath), true);

        // Check if the JSON was decoded successfully and is an array
        if (!is_array($statesData)) {
            $this->command->error("Could not decode states JSON data or it's in an unexpected format. File may be malformed or not an array of objects.");
            return;
        }

        // Disable foreign key checks for the duration of the seeding to prevent errors
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing state data before seeding to prevent duplicates on re-run
        State::truncate(); // Use the State model for truncating

        $this->command->info('Truncated existing state data.');

        // Prepare data for insertion
        $insertData = [];
        foreach ($statesData as $state) {
            // Ensure necessary keys exist in each JSON object
            if (isset($state['code']) && isset($state['name'])) {
                $insertData[] = [
                    'state_code' => $state['code'],
                    'name' => $state['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            } else {
                $this->command->warn("Skipping malformed state entry in JSON: " . json_encode($state));
            }
        }

        // Insert the data into the database in chunks for performance
        $chunkSize = 500; // You can adjust this chunk size
        foreach (array_chunk($insertData, $chunkSize) as $chunk) {
            State::insert($chunk); // Use the State model for inserting
            $this->command->info("Inserted a chunk of " . count($chunk) . " states.");
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('State seeding completed successfully from JSON!');
    }
}
