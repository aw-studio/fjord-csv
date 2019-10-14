<?php

namespace AwStudio\FjordCsv;

use AwStudio\Fjord\Support\Facades\FjordRoute;
use AwStudio\Fjord\Form\Requests\CrudReadRequest;
use AwStudio\FjordCsv\DataExport;
use Maatwebsite\Excel\Facades\Excel;

/**
 * CrudIndexExport
 */
trait CrudIndexExport
{
    public function addExportExtension()
    {
        return [
            'index.actions' => ['crud-index-export']
        ];
    }

    public function export(CrudReadRequest $request)
    {
        $data = $this->model::whereIn('id', $request->ids)
            ->get();

        return Excel::download(new DataExport($data), 'data.csv', \Maatwebsite\Excel\Excel::CSV);

    }

    public function makeExportRoute()
    {
        FjordRoute::post("/{$this->titlePlural}/export", self::class . "@export")
            ->name("{$this->titlePlural}.export");
    }
}
