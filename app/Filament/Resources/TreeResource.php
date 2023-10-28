<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TreeResource\Pages;
use App\Models\AgencyCity;
use App\Models\AgencyProvider;
use App\Models\City;
use App\Models\Provider;
use App\Models\Service;
use App\Models\Tree;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TreeResource\RelationManagers;
use App\Models\ContentTree;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentAdjacencyList\Forms\Components\AdjacencyList;

class TreeResource extends Resource
{
    protected static ?string $model = Tree::class;

    protected static ?string $navigationGroup = 'Estrutura';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationLabel = 'Árvores';
    protected static ?string $modelLabel = 'Árvore';
    protected static ?string $pluralModelLabel = 'Árvores';
    protected static ?string $navigationIcon = 'lucide-folder-tree';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                ->schema([
                    TextInput::make('nome')
                        ->label('Nome da árvore')
                        ->required()
                        ->unique(ignoreRecord:true),

                    Select::make('provider_codigo')
                        ->label('Prestador')
                        ->searchable()
                        ->options(Provider::whereIn('codigo', AgencyProvider::pluck('provider_codigo'))->orderBy('nome')->pluck('nome', 'codigo')),

                    Select::make('city_codigo')
                        ->label('Município')
                        ->searchable()
                        ->options(City::whereIn('codigo', AgencyCity::pluck('city_codigo'))->orderBy('nome')->pluck('nome', 'codigo')),

                    Select::make('service_id')
                        ->label('Serviço')
                        ->options(Service::pluck('nome', 'id')),

                    AdjacencyList::make('content_tree')
                        ->label('Árvore de desdobramento')
                        ->labelKey('name')
                        ->childrenKey('children')
                        ->extraAttributes(['team_id'], 1)
                        // ->deleteAction(function (
                        //     AdjacencyList $component,
                        //     Action $action,
                        //     ?Model $record,
                        //     ?array $state
                        // ) {
                        //     $action
                        //         // ->iconButton()->icon('heroicon-o-trash')->color('danger')
                        //         // ->label(fn (): string => __('filament-adjacency-list::adjacency-list.actions.delete.label'))
                        //         // ->modalIcon('heroicon-o-trash')
                        //         // ->modalHeading(fn (): string => __('filament-adjacency-list::adjacency-list.actions.delete.modal.heading'))
                        //         // ->modalSubmitActionLabel(fn (): string => __('filament-adjacency-list::adjacency-list.actions.delete.modal.actions.confirm'))
                        //         ->action(
                        //             function (array $arguments, AdjacencyList $component): void {
                        //                 $statePath = $component->getRelativeStatePath($arguments['statePath']);
                        //                 $items = $component->getState();
                        //                 $uuid = substr($statePath, -36);

                        //                 // $model = ContentTree::where('uuid', $uuid);

                        //                 data_forget($items, $statePath);

                        //                 $component->state($items);
                        //             }
                        //         )
                        //         ->button()
                        //         ->requiresConfirmation()
                        //         ;

                        //     return $action;
                        // })
                        ->form([
                            TextInput::make('name')
                                ->required(),
                        ]),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Código')
                    ->sortable(),
                TextColumn::make('nome')
                    ->label('Nome da árvore')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('agency.nome')
                    ->label('Agência')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('providerName.nome')
                    ->label('Prestador')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cityName.nome')
                    ->label('Município')
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
            RelationManagers\ContentTreeRelationManager::class,
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
            'index' => Pages\ListTrees::route('/'),
            'create' => Pages\CreateTree::route('/create'),
            'edit' => Pages\EditTree::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
