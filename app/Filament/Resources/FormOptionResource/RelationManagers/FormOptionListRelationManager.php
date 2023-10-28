<?php

namespace App\Filament\Resources\FormOptionResource\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FormOptionListRelationManager extends RelationManager
{
    protected static string $relationship = 'formOptionList';
    protected static ?string $title = 'Opções de Resposta';

    protected static ?string $navigationLabel = 'Opções de Resposta';
    protected static ?string $modelLabel = 'Opções de Resposta';
    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('opcao')
                    ->label('Opção')
                    ->required()
                    ->maxLength(255),
                Select::make('conforme')
                    ->label('Conformidade')
                    ->options([
                        0 => 'Não se aplica',
                        1 => 'Conforme',
                        2 => 'Não conforme',
                    ])
                    ->required()
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('option')
            ->columns([
                TextColumn::make('opcao')
                    ->label('Opção'),
                TextColumn::make('conforme')
                    ->label('Conformidade')
                    ->formatStateUsing(
                        function (string $state) {
                            $listOptions = [
                                0 => 'Não se aplica',
                                1 => 'Conforme',
                                2 => 'Não conforme',
                            ];

                            return $listOptions[$state] ?? 0;
                        }
                    ),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                // Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    // Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }
}
