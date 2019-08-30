<?php

namespace App\Imports;

use App\Models\State;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class StateImport implements ToCollection {
    
    public function collection(Collection $rows) {
        foreach ($rows as $row) {
            State::create(['title' => $row[0]]);    
        }
    }
}
