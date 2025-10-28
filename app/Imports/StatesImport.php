<?php

namespace App\Imports;

use App\Models\State;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StatesImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new State([
            'state_code' => $row['code'],
            'name' => $row['name'],
        ]);
    }

    /**
     * Define the heading row number.
     * @return int
     */
    public function headingRow(): int
    {
        return 1;
    }
}
