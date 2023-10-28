<?php

namespace App\Filament\Resources\AuditFormListResource\RelationManagers;

use App\Models\ActionType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PlanRelationManager extends RelationManager
{
    protected static string $relationship = 'plans';
    protected static ?string $title = 'Plano de Ação';

    protected static ?string $navigationLabel = 'Ação';
    protected static ?string $modelLabel = 'Ação';
    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns(4)
                    ->schema([
                        Textarea::make('acao')
                            ->label('Ação')
                            ->required()
                            // ->columnSpanFull()
                            ->columnSpan(4)
                            ->rows(3),
                        TextInput::make('responsavel')
                            ->label('Responsável')
                            ->required()
                            ->columnSpan(2)
                            ->maxLength(255),
                        TextInput::make('onde')
                            ->label('Onde')
                            ->columnSpan(2)
                            ->maxLength(255),
                        DatePicker::make('inicio_p')
                            ->label('Início Previsto')
                            ->columnSpan(1)
                            ->required(),
                        DatePicker::make('termino_p')
                            ->label('Término Previsto')
                            ->columnSpan(1)
                            ->required(),
                        DatePicker::make('inicio_r')
                            ->columnSpan(1)
                            ->label('Início Real'),
                        DatePicker::make('termino_r')
                            ->columnSpan(1)
                            ->label('Término Real'),
                        TextArea::make('observacao')
                            ->label('Observação')
                            ->columnSpan(3)
                            ->rows(2),
                        Radio::make('action_type_id')
                            ->label('Tipo de Ação')
                            ->columnSpan(1)
                            ->default(1)
                            ->options(ActionType::orderBy('action_type')->pluck('action_type', 'id')),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('acao')
            ->columns([
                Tables\Columns\TextColumn::make('acao'),
                Tables\Columns\TextColumn::make('responsavel'),
                Tables\Columns\TextColumn::make('inicio_p'),
                Tables\Columns\TextColumn::make('termino_p'),
                Tables\Columns\TextColumn::make('inicio_r'),
                Tables\Columns\TextColumn::make('termino_r'),
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
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
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
