<?php

namespace App\Filament\Resources\AgencyResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class DomainsRelationManager extends RelationManager
{
    protected static string $relationship = 'domains';
    protected static ?string $label = 'Domínio';

    protected static ?string $title = 'Domínios';
    protected static ?string $pluralLabel = 'Domínios';
    protected static ?string $modelLabel = 'Domínio';
    protected static ?string $pluralModelLabel = 'Domínios';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('domain')
                    ->unique()
                    ->regex('/^(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z]{2,}$/i') // Valida domínio
                    ->suffixIcon('heroicon-m-globe-alt')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('domain')
            ->columns([
                Tables\Columns\TextColumn::make('domain'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->afterFormValidated(function ($data) {
                        $domain = $data['domain'] ?? null;
                        $regex = '/^(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z]{2,}$/i';

                        if (!preg_match($regex, $domain)) {
                            // O domínio é inválido

                            //TODO: Enviar notificação para o usuário e invalidar o formulário
                            dd('O domínio informado é inválido'); // TODO
                        }

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
