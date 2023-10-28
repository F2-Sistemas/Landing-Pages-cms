<?php

namespace App\Filament\Resources\AuditFormListResource\RelationManagers;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NonconformitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'nonconformities';
    protected static ?string $title = 'Não Conformidades';

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                ->schema([
                    TextInput::make('pergunta')
                        ->label('Pergunta')
                        ->required()
                        ->maxLength(255)
                        ->disabled(),
                    TextInput::make('referencia')
                        ->label('Referência')
                        ->required()
                        ->maxLength(255),
                    Textarea::make('recomendacao')
                        ->label('Recomendação')
                        ->required()
                        ->rows(3),
                    DatePicker::make('prazo')
                        ->label('Prazo')
                        ->required(),
                ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('audit_form_list_id')
            ->columns([
                Tables\Columns\TextColumn::make('pergunta'),
                Tables\Columns\TextColumn::make('referencia'),
                Tables\Columns\TextColumn::make('recomendacao'),
                Tables\Columns\TextColumn::make('prazo'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make()
                //     ->label('Adicionar'),
                // Ajuda: Colocar o botao de gerar nao conformidades aqui
                // \App\Filament\Actions\GerarNaoConformiadesAction::make()->label('Gerar não conformidades'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->modalHeading('Editar Não Conformidade'),
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
                Tables\Actions\CreateAction::make()->label('Adicionar Não Conformidade'),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }
}
