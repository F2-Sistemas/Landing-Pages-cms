<?php

namespace App\Filament\Resources\AuditFormListResource\Pages;

use App\Filament\Resources\AuditFormListResource;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class CalendarAuditFormLists extends Page
{
    protected static string $resource = AuditFormListResource::class;

    protected static string $view = 'filament.resources.audit-form-list-resource.pages.audit-form-list-calendar';

    public function getTitle(): string|Htmlable
    {
        return __('models.AuditFormLists.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('models.AuditFormLists.navigation_label');
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\CalendarWidget::class,
        ];
    }
}
