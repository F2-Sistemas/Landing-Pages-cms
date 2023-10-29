<?php

namespace App\Filament\Widgets;

class AccountWidget extends \Filament\Widgets\Widget
{
    protected static ?int $sort = -3;

    /**
     * @var string $view
     */
    protected static string $view = 'filament.widgets.account-widget';

    public static function canView(): bool
    {
        return !(tenancy()->initialized);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        return [];
    }
}
