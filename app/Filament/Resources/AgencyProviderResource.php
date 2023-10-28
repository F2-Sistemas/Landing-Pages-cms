<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgencyProviderResource\Pages;
use App\Models\AgencyProvider;
use App\Models\Provider;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class AgencyProviderResource extends Resource
{
    protected static ?string $model = AgencyProvider::class;

    protected static ?string $navigationGroup = 'Estrutura';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Prestadores';

    protected static ?string $modelLabel = 'Prestadore';
    protected static ?string $pluralModelLabel = 'Prestadores';

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('provider_codigo')
                    ->label('Prestador')
                    ->required()
                    ->searchable()
                    ->columnSpanFull()
                    ->getSearchResultsUsing(
                        fn (string $search): array => Provider::whereRaw(
                            'LOWER(nome) like ?',
                            [
                                strtolower("%{$search}%"),
                            ]
                        )
                            ->limit(50)
                            ->get()
                            ?->map(function (?Model $record) {
                                return [
                                    'label' => "{$record->nome} | {$record->city?->nome} - {$record->city?->uf}",
                                    'codigo' => $record?->codigo,
                                ];
                            })
                            ?->pluck('label', 'codigo')
                            ?->toArray()
                    )

                    ->getOptionLabelUsing( // alternativa ao titleAttribute
                        function ($value): ?string {
                            $provider = Provider::with('city')->whereCodigo($value)->first();

                            if (! $provider) {
                                return null;
                            }

                            return "{$provider->nome} | {$provider->city?->nome} - {$provider->city?->uf}";
                        }
                    ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('provider.nome')
                    ->label('Prestador')
                    ->searchable()
                    ->sortable()
                    ->searchable(
                        isIndividual: true
                    ),

                TextColumn::make('provider.city.nome')
                    ->label('Sede do prestador')
                    ->searchable()
                    ->sortable()
                    ->searchable(
                        isIndividual: true
                    ),

                TextColumn::make('provider.city.uf')
                    ->label('Sigla do estado')
                    ->searchable()
                    ->sortable()
                    ->searchable(
                        isIndividual: true
                    ),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
                ExportBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAgencyProviders::route('/'),
            'create' => Pages\CreateAgencyProvider::route('/create'),
            'edit' => Pages\EditAgencyProvider::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
