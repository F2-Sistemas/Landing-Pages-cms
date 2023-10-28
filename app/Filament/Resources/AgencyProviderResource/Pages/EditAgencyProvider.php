<?php

namespace App\Filament\Resources\AgencyProviderResource\Pages;

use App\Filament\Resources\AgencyProviderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAgencyProvider extends EditRecord
{
    protected static string $resource = AgencyProviderResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
