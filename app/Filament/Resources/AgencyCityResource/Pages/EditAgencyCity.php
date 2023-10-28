<?php

namespace App\Filament\Resources\AgencyCityResource\Pages;

use App\Filament\Resources\AgencyCityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAgencyCity extends EditRecord
{
    protected static string $resource = AgencyCityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
