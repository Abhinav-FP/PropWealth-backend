<?php

namespace App\Imports;

use App\Models\Suburb;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SuburbsImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Export the individual bounding box coordinates
        $min_lng = $row['min_lng'] ?? null;
        $min_lat = $row['min_lat'] ?? null;
        $max_lng = $row['max_lng'] ?? null;
        $max_lat = $row['max_lat'] ?? null;

        // Check if all necessary bbox components are present before creating the array
        if (is_null($min_lng) || is_null($min_lat) || is_null($max_lng) || is_null($max_lat)) {
            return null;
        }

        // Create the bounding box array in the format
        $bboxArray = [(float)$min_lng, (float)$min_lat, (float)$max_lng, (float)$max_lat];

        // Encode the array into a Json string for the 'bbox' database column
        $bboxJsonString = json_encode($bboxArray);

        return new Suburb([
            'suburb_id' => $row['id'],
            'name' => $row['name'],
            'state' => $row['state'],
            'bbox' => $bboxJsonString,
        ]);
    }

    public function headingRow(): int
    {
        return 1;
    }
}
