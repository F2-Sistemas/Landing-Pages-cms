<?php

namespace App\Filament\Resources\TreeResource\RelationManagers;

use App\Models\ContentTree;
use App\Models\Tree;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContentTreeRelationManager extends RelationManager
{
    protected static string $relationship = 'contentTrees';
    protected static ?string $modelLabel = 'Conteúdo das Árvores';
    protected static ?string $navigationLabel = 'Conteúdo das Árvores';

    public static $optionsTree = [];

    public static function recursiveTree($tree_id, $id = null, $pai_id = null, $nivel = 0)
    {
        // \Log::alert('nivel: ' .  $nivel);
        $conteudo = ContentTree::where(function ($query) use ($tree_id, $id, $pai_id) {
            $query->where('tree_id', $tree_id);

            if (!empty($id)) {
                $query->whereNot('id', $id);
            }

            if (empty($pai_id)) {
                $query->whereNull('pai_id');
            } else {
                $query->where('pai_id', $pai_id);
            }
        })->pluck('nome', 'id');

        if (empty($pai_id)) {
            self::$optionsTree[0] = 'Nenhum nível superior';
        }

        foreach ($conteudo as $key => $value) {
            self::$optionsTree[$key] = str_pad($value, strlen($value) + ($nivel * 2), '-', STR_PAD_LEFT);
            // \Log::alert('ID: ' .  $key . " - " . $value . " - " . $nivel);

            $conteudoFilho = ContentTree::where('tree_id', $tree_id)->whereNot('id', $id)->where('pai_id', $key)->pluck('nome', 'id');

            if (!empty($conteudoFilho)) {
                self::recursiveTree($tree_id, $id, $key, ($nivel + 1));
            }
        }

        // return $arvore;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                ->schema([
                    TextInput::make('nome')
                        ->label('Nome')
                        ->required()
                        ->unique(ignoreRecord:true),
                    Select::make('tree_id')
                        ->label('Árvore')
                        ->required()
                        ->searchable()
                        ->options(Tree::pluck('nome', 'id')),
                    Select::make('pai_id')
                        ->label('Item Superior (pai)')
                        ->searchable()
                        ->options(function (\Filament\Forms\Get $get) {
                            // \Log::alert('www: ' .  print_r($x, true));
                            self::recursiveTree($get('tree_id'), $get('id'));

                            return self::$optionsTree;
                        })
                    ,
                ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('option')
            ->columns([
                TextColumn::make('id')
                    ->label('Código')
                    ->sortable(),
                TextColumn::make('nome')
                    ->label('Nome do Item')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('uuid_pai')
                    ->label('Nível superior')
                    ->formatStateUsing(function (?Model $record) {
                        if (empty($record->uuid_pai)) {
                            return;
                        }

                        return ContentTree::where('uuid', $record->uuid_pai)->first()->nome;
                    })
                    ->searchable()
                    ->sortable(),
            ])
            // ->filters([
            //     Tables\Filters\TrashedFilter::make()
            // ])
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
        ->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]);
    }
}
