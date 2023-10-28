<?php

namespace App\Filament\Resources\ReportResource\Pages;

use App\Filament\Resources\ReportResource;
use App\Models\Report;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditReport extends EditRecord
{
    protected static string $resource = ReportResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $id = $data['id'];
        unset($data['id']);
        $config = [];

        foreach ($data as $key => $value) {
            if (!in_array($key, ['id', 'titulo', 'conteudo'])) {
                $config[$key] = $value;
                unset($data[$key]);
            }
        }
        $data['config'] = $config;
        $dataUpdate = new Report();
        $dataUpdate = Report::updateOrCreate(
            [
                'id' => $id,
            ],
            $data
        );
        $fileName = 'Relatorio_' . $id . '.docx';

        if (file_exists(storage_path('app/public/reports/' . $fileName))) {
            unlink(storage_path('app/public/reports/' . $fileName));
        }

        return $dataUpdate;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
