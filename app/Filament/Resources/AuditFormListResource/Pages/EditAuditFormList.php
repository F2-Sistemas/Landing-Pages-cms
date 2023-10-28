<?php

namespace App\Filament\Resources\AuditFormListResource\Pages;

use App\Filament\Resources\AuditFormListResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\AnswerResource;

class EditAuditFormList extends EditRecord
{
    protected static string $resource = AuditFormListResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make()
                // ->hidden(fn(Model $record) => boolval($record?->answers()?->count()))
                ->disabled(fn (Model $record) => boolval($record?->answers()?->count()))
                ->icon('heroicon-o-trash'),

            Actions\Action::make('fill_form')
                ->label(__('models.AuditFormList.fill_form'))
                ->icon('heroicon-o-pencil-square')
                ->url(
                    fn (Model $record) => AnswerResource::getUrl('edit', [
                        'record' => $record,
                    ])
                ),
        ];
    }
}
