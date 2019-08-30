<?php

namespace App\Imports;

use App\Models\City;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class CityImport implements ToCollection {
    public function collection(Collection $cities) {
        foreach ($cities as $city) {
            City::create([
                'title' => $city[0],

                'state_id' => $city[1]
            ]);
        }
    }
}
