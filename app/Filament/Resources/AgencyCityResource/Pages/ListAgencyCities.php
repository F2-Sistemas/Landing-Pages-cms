<?php

namespace App\Filament\Resources\AgencyCityResource\Pages;

use App\Filament\Resources\AgencyCityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAgencyCities extends ListRecords
{
    protected static string $resource = AgencyCityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
