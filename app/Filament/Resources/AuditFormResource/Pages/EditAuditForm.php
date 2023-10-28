<?php

namespace App\Filament\Resources\AuditFormResource\Pages;

use App\Filament\Resources\AuditFormResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAuditForm extends EditRecord
{
    protected static string $resource = AuditFormResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function mount(int | string $record): void
    {
        parent::mount($record);

        if ($this->record?->auditFormLists()?->with('answers')?->whereHas('answers')?->count()) {
            \Filament\Notifications\Notification::make()
                ->title('Formulário já respondido!')
                ->warning()
                ->body('Já existem fiscalizações correlacionadas a este formulário. Qualquer alteração poderá afetar')
                ->persistent()
                ->color('warning')
                ->send();
        }
    }
}
