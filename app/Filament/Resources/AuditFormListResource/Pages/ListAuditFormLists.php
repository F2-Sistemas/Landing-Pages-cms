<?php

namespace App\Filament\Resources\AuditFormListResource\Pages;

use App\Filament\Resources\AuditFormListResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAuditFormLists extends ListRecords
{
    protected static string $resource = AuditFormListResource::class;

    protected function getActions(): array
    {
        return [
            Actions\Action::make('calendar')
                ->label(__('models.AuditFormLists.actions.goto_calendar'))
                ->url(fn () => app(static::getResource())->getUrl('calendar'))
                ->icon('heroicon-o-calendar-days'),

            Actions\CreateAction::make()
                ->label(__('models.AuditFormLists.actions.create'))
                ->icon('heroicon-o-plus'),
        ];
    }
}
