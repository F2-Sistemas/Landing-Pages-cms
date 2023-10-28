<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('create')
                ->url(UserResource::getUrl('create'))
                ->label(__('models.User.actions.create')),

            Actions\DeleteAction::make()
                ->label(__('models.User.actions.delete')),

            Actions\RestoreAction::make()
                ->label(__('models.User.actions.restore')),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (empty($data['password'])) {
            unset($data['password']);
        }

        return $data;
    }
}
