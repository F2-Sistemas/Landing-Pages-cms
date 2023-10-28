<?php

namespace App\Filament\Resources\FormOptionResource\Pages;

use App\Filament\Resources\FormOptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFormOption extends EditRecord
{
    protected static string $resource = FormOptionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
