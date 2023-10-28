<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditFormListResource\Pages;
use App\Filament\Resources\AuditFormListResource\RelationManagers;
use App\Models\AuditForm;
use App\Models\AuditFormList;
use App\Models\ContentTree;
use App\Models\Tree;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AuditFormListResource extends Resource
{
    protected static ?string $model = AuditFormList::class;

    protected static ?string $navigationGroup = 'Realização';

    protected static ?int $navigationSort = 21;

    protected static ?string $navigationLabel = 'Fiscalizações';

    protected static ?string $modelLabel = 'Fiscalização';

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $recordTitleAttribute = 'tree.nome';

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'titulo',
            'tree.nome',
            'contentTree.nome',
            'auditForm.titulo',
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Título' => $record->titulo,
            'Árvore' => $record->tree?->nome,
            'Fiscalização' => $record->contentTree?->nome,
            'Formulário' => $record->auditForm?->titulo,
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('titulo')
                            ->label('Título')
                            ->columnSpan(2)
                            ->required(),
                        // Falta conseguir pegar qual o codigo da árvore
                        Select::make('tree_id')
                            ->label('Selecione a árvore')
                            ->columnSpan(1)
                            ->required()
                            ->reactive()
                            ->options(Tree::pluck('nome', 'id')),
                        Select::make('content_tree_id')
                            ->label('Onde será aplicado?')
                            ->columnSpan(1)
                            ->reactive()
                            ->hidden(function (\Filament\Forms\Get $get) {
                                if (empty($get('tree_id'))) {
                                    return true;
                                }
                            })
                            ->options(
                                fn (\Filament\Forms\Get $get) => ContentTree::where('tree_id', $get('tree_id'))->pluck('nome', 'id')
                            ),
                        Select::make('audit_form_id')
                            ->label('Selecione o formulário')
                            ->columnSpan(2)
                            ->required()
                            ->options(AuditForm::pluck('titulo', 'id')),
                        DatePicker::make('data_inicio')
                            ->label('Data de início')
                            ->default(now())
                            ->columnSpan(1)
                            ->reactive()
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('data_termino', $state)),
                        DatePicker::make('data_termino')
                            ->label('Data de término')
                            ->columnSpan(1)
                            ->reactive()
                            ->minDate(fn (\Filament\Forms\Get $get) => $get('data_inicio'))
                            ->default(now()),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('titulo')
                    ->label('Título')
                    ->sortable(),
                TextColumn::make('tree.nome')
                    ->label('Árvore')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('contentTree.nome')
                    ->label('Fiscalização')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('auditForm.titulo')
                    ->label('Formulário')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('data_inicio')
                    ->label('Data de início')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('data_termino')
                    ->label('Data de término')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('respondido')
                    ->label('Respondido?')
                    // ->options([
                    //     'heroicon-o-x-circle'     => fn ($state, $record): bool => $record->deleted_at != '',
                    //     'heroicon-o-check-circle' => fn ($state, $record): bool => $record->deleted_at == '',
                    // ])
                    // ->colors([
                    //     'danger'  => fn ($state, $record): bool => $record->deleted_at != '',
                    //     'success' => fn ($state, $record): bool => $record->deleted_at == '',
                    // ])
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('edit')
                    ->label('Responder')
                    ->url(
                        fn (Model $record) => AnswerResource::getUrl('edit', [
                            'record' => $record,
                        ])
                    )
                    ->icon('heroicon-o-pencil-square')
                    ->openUrlInNewTab(false),
                \App\Filament\Actions\GerarNaoConformiadesAction::make()->label('Gerar não conformidades'),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function getRelations(): array
    {
        return [
            // RelationManagers\PlanRelationManager::class,
            RelationManagers\NonconformitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuditFormLists::route('/'),
            'calendar' => Pages\CalendarAuditFormLists::route('/calendar'),
            'create' => Pages\CreateAuditFormList::route('/create'),
            'edit' => Pages\EditAuditFormList::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\CalendarWidget::class,
        ];
    }
}
