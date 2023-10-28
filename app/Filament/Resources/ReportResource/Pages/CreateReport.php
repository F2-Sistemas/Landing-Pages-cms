<?php

namespace App\Filament\Resources\ReportResource\Pages;

use App\Filament\Resources\ReportResource;
use App\Models\Report;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateReport extends CreateRecord
{
    protected static string $resource = ReportResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $config = [];

        foreach ($data as $key => $value) {
            if (!in_array($key, ['id', 'titulo', 'conteudo'])) {
                $config[$key] = $value;
                unset($data[$key]);
            }
        }

        $dataInsert = new Report();
        $dataInsert->titulo = $data['titulo'];
        $dataInsert->conteudo = $data['conteudo'];
        $dataInsert->config = $config;
        $dataInsert->save();

        return $dataInsert;
    }
}
