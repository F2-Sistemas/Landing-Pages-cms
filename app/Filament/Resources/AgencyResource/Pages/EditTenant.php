<?php

namespace App\Filament\Resources\AgencyResource\Pages;

use App\Filament\Resources\AgencyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTenant extends EditRecord
{
    protected static string $resource = AgencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['meta']['config'] = array_merge(
            [
                'facebook' => null,
                'linkedin' => null,
            ],
            $data['meta']['config'] ?? [],
        );

        return $data;
    }

    public function getTitle(): \Illuminate\Contracts\Support\Htmlable|string
    {
        return static::getRecordTitle();
    }
}
