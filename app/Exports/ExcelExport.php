<?php

namespace App\Exports;

use App\batch_collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExcelExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return batch_collection::all();
    }
}
