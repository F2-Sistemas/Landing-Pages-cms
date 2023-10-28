<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FormOptionResource\Pages;
use App\Filament\Resources\FormOptionResource\RelationManagers;
use App\Models\FormOption;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FormOptionResource extends Resource
{
    protected static ?string $model = FormOption::class;

    protected static ?string $navigationGroup = 'Cadastro';
    protected static ?int $navigationSort = 12;
    protected static ?string $navigationLabel = 'Tipos de Respostas';
    protected static ?string $modelLabel = 'Tipos de Respostas';
    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nome')
                    ->label('Tipo de respostas')
                    ->required()
                    ->unique(ignoreRecord:true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nome')
                    ->label('Tipo de respostas')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\FormOptionListRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFormOptions::route('/'),
            'create' => Pages\CreateFormOption::route('/create'),
            'edit' => Pages\EditFormOption::route('/{record}/edit'),
        ];
    }
}
