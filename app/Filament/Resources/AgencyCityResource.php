<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgencyCityResource\Pages;
use App\Models\AgencyCity;
use App\Models\City;
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

class AgencyCityResource extends Resource
{
    protected static ?string $model = AgencyCity::class;

    protected static ?string $navigationGroup = 'Estrutura';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Municípios';

    protected static ?string $modelLabel = 'Município';
    protected static ?string $pluralModelLabel = 'Municípios';

    protected static ?string $navigationIcon = 'fas-city';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('city_codigo')
                    ->label('Sigla do estado - município')
                    ->searchable()
                    ->required()
                    ->getSearchResultsUsing(
                        fn (string $search): array => City::whereRaw(
                            'LOWER(nome) like ?',
                            [
                                strtolower("%{$search}%"),
                            ]
                        )
                            ->limit(50)
                            ->get()
                            ?->map(function (?Model $record) {
                                return [
                                    'label' => "{$record->nome} - {$record?->uf}",
                                    'codigo' => $record?->codigo,
                                ];
                            })
                            ?->pluck('label', 'codigo')
                            ?->toArray()
                    )
                    ->getOptionLabelUsing( // alternativa ao titleAttribute
                        function ($value): ?string {
                            $city = City::whereCodigo($value)->first();

                            if (! $city) {
                                return null;
                            }

                            return "{$city->nome} - {$city?->uf}";
                        }
                    ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('city.nome')
                    ->label('Município')
                    ->searchable()
                    ->searchable(
                        isIndividual: true
                    )
                    ->sortable(),

                TextColumn::make('city.uf')
                    ->label('Sigla do estado')
                    ->searchable()
                    ->searchable(
                        isIndividual: true
                    )
                    ->sortable(),
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
            'index' => Pages\ListAgencyCities::route('/'),
            'create' => Pages\CreateAgencyCity::route('/create'),
            'edit' => Pages\EditAgencyCity::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
