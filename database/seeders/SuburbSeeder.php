<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Suburb;
use Illuminate\Support\Facades\File;

class SuburbSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Path to your JSON data file
        $filePath = database_path('seeders/data/suburbs.json');

        // Ensure the file exists before attempting to read it
        if (!File::exists($filePath)) {
            $this->command->error("Suburbs JSON file not found at: {$filePath}");
            return;
        }

        // Decode the JSON data into a PHP array
        $suburbsData = json_decode(File::get($filePath), true);

        // Check if the JSON was decoded successfully and has a 'features' key
        if (!is_array($suburbsData) || !isset($suburbsData['features'])) {
            $this->command->error("Could not decode suburbs JSON data or it's in an unexpected format. File may be malformed or missing the 'features' key.");
            return;
        }

        $suburbs = $suburbsData['features'];

        // Set the chunk size. A value like 500 or 1000 is usually safe.
        $chunkSize = 1000;

        // Chunk the suburbs data into smaller arrays
        $chunks = array_chunk($suburbs, $chunkSize);

        // Disable foreign key checks for the duration of the seeding to prevent errors
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate the table before seeding to start fresh
        Suburb::truncate();

        // Loop through each chunk and insert the data
        foreach ($chunks as $chunk) {
            // Prepare data for insertion (e.g., add timestamps)
            $insertData = collect($chunk)->map(function ($suburb) {
                // Ensure the suburb entry is an array with 'properties' and 'bbox' keys
                if (is_array($suburb) && isset($suburb['properties']) && isset($suburb['bbox'])) {
                    return [
                        'suburb_id' => $suburb['id'],
                        'name' => $suburb['properties']['name'],
                        'state' => $suburb['properties']['state'],
                        // Encode the bbox array to a JSON string
                        'bbox' => json_encode($suburb['bbox']),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                // Log a warning for malformed entries and return null
                $this->command->warn("Skipping malformed suburb entry.");
                return null;
            })->filter()->toArray(); // Filter out any null values

            // Only insert if there is data in the chunk
            if (!empty($insertData)) {
                // Insert the chunk of data into the database
                Suburb::insert($insertData);
                $this->command->info("Inserted a chunk of " . count($insertData) . " suburbs.");
            }
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('Suburb seeding completed successfully!');
    }
}
