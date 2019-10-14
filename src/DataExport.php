<?php

namespace AwStudio\FjordCsv;

use Maatwebsite\Excel\Concerns\FromCollection;

class DataExport implements FromCollection
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }
}
