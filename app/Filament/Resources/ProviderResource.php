<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProviderResource\Pages;
use App\Models\City;
use App\Models\Provider;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProviderResource extends Resource
{
    protected static ?string $model = Provider::class;

    protected static ?string $navigationGroup = 'Global';
    protected static ?int $navigationSort = 42;
    protected static ?string $navigationLabel = 'Prestadores';
    protected static ?string $modelLabel = 'Prestador';
    protected static ?string $pluralModelLabel = 'Prestadores';
    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('codigo')
                            ->label('Código')
                            ->required()
                            ->integer()
                            ->unique(ignoreRecord: true),
                        TextInput::make('nome')
                            ->label('Nome do prestador')
                            ->required()
                            ->unique(ignoreRecord: true)
                        ,
                        Select::make('city_codigo')
                            ->label('Município sede')
                            ->searchable()
                            ->required()
                            ->getSearchResultsUsing(
                                fn (string $search): array => City::whereRaw(
                                    "LOWER(nome) like ?",
                                    [
                                        strtolower("%{$search}%")
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
                            ->getOptionLabelUsing(
                                // alternativa ao titleAttribute
                                function ($value): ?string {
                                    $city = City::whereCodigo($value)->first();

                                    if (!$city) {
                                        return null;
                                    }

                                    return "{$city->nome} - {$city?->uf}";
                                }
                            ),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('codigo')
                    ->label('Código')
                    ->sortable(),
                TextColumn::make('nome')
                    ->label('Nome do prestador')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('city.nome')
                    ->label('Município sede')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('city.uf')
                    ->label('Sigla do estado')
                    ->searchable()
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
            'index' => Pages\ListProviders::route('/'),
            'create' => Pages\CreateProvider::route('/create'),
            'edit' => Pages\EditProvider::route('/{record}/edit'),
        ];
    }
}
