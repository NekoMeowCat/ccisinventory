<?php

namespace App\Imports;

use App\Models\Equipment;
use App\Models\Facility;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use Illuminate\Support\Facades\Log;


class EquipmentImport implements ToModel, WithHeadingRow
{



    use Importable;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function model(array $row)
    {
        // dd($row);
        $facility = Facility::where('name', $row['facility_id'])->first();
        $category = Category::where('name', $row['category_id'])->first();


        $data = [
            'unit_no' => $row['unit_no'],
            'description' => $row['description'],
            'specifications' => $row['specifications'],
            'facility_id' => $facility->id,
            'category_id' => $category->id,
            'status' => $row['status'],
            'date_acquired' => $row['date_acquired'],
            'supplier' => $row['supplier'],
            'amount' => $row['amount'],
            'estimated_life' => $row['estimated_life'],
            'item_no' => $row['item_no'],
            'property_no' => $row['property_no'],
            'control_no' => $row['control_no'],
            'serial_no' => $row['serial_no'],
            'no_of_stocks' => $row['no_of_stocks'],
            'restocking_point' => $row['restocking_point'],
            'person_liable' => $row['person_liable'],
            'remarks' => $row['remarks'],
        ];


        $equipment = new Equipment($data);

        return $equipment;

        // dd($equipment);
    }
}
