<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CityResource\Pages;
use App\Models\City;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CityResource extends Resource
{
    protected static ?string $model = City::class;

    protected static ?string $navigationGroup = 'Global';

    protected static ?int $navigationSort = 41;

    protected static ?string $navigationLabel = 'Municípios';

    protected static ?string $modelLabel = 'Município';
    protected static ?string $pluralModelLabel = 'Municípios';

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

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
                            ->label('Nome do município')
                            ->required()
                            ->unique(ignoreRecord: true),

                        TextInput::make('uf')
                            ->required()
                            ->label('Sigla do estado'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nome')
                    ->label('Nome do município')
                    ->searchable(
                        isIndividual: true
                    )
                    ->sortable(),

                Tables\Columns\TextColumn::make('codigo')
                    ->label('Código')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn (?Model $record) => $record ? "{$record?->codigo}" : '')
                    ->searchable(
                        query: function (Builder $query, string $search) {
                            $search = trim($search);

                            if (! $search || ! is_numeric($search)) {
                                return $query->where('id', 'ilike', 0);
                            }

                            return $query->where('codigo', 'ilike', $search . '%');
                        },
                        isIndividual: true
                    ),

                Tables\Columns\TextColumn::make('uf')
                    ->label('Sigla do estado')
                    ->sortable()
                    ->searchable(
                        query: function (Builder $query, string $search) {
                            $search = trim($search);

                            if (! $search || strlen($search) > 2) {
                                return $query->where('id', 'ilike', 0);
                            }

                            return $query->where('uf', 'ilike', $search . '%');
                        },
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
            'index' => Pages\ListCities::route('/'),
            'create' => Pages\CreateCity::route('/create'),
            'edit' => Pages\EditCity::route('/{record}/edit'),
        ];
    }
}
