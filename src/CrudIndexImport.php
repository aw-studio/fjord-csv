<?php

namespace AwStudio\FjordCsv;

use AwStudio\Fjord\Support\Facades\FjordRoute;
use AwStudio\Fjord\Form\Requests\CrudCreateRequest;

/**
 * CrudIndexExport
 */
trait CrudIndexImport
{
    public function addImportExtension()
    {
        return [
            'index.globalActions' => ['crud-index-import']
        ];
    }

    public function import(CrudCreateRequest $request)
    {
        $model = $request->model;
        $count = 0;
        foreach ($request->data as $item) {
            $newModel = $model::create($item);
            $count++;
        }
        return response()->json([
            'message' => "Imported {$count} item" . ($count > 1 ? 's' : '')
        ], 200); 
    }

    public function getFillable(CrudCreateRequest $request)
    {
        $model = $request->model;
        $model = new $model;
        return $model->getFillable();
    }

    public function makeImportRoute()
    {
        FjordRoute::post("/{$this->titlePlural}/import", self::class . "@import")
            ->name("{$this->titlePlural}.import");
    }

    public function makeGetFillableRoute()
    {
        FjordRoute::post("/{$this->titlePlural}/getFillable", self::class . "@getFillable")
            ->name("{$this->titlePlural}.getFillable");
    }
}
