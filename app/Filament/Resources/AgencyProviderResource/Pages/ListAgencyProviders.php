<?php

namespace App\Filament\Resources\AgencyProviderResource\Pages;

use App\Filament\Resources\AgencyProviderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAgencyProviders extends ListRecords
{
    protected static string $resource = AgencyProviderResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
