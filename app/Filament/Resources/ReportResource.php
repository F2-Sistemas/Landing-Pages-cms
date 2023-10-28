<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Models\AuditForm;
use App\Models\AuditFormList;
use App\Models\Report;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Builder as ComponentsBuilder;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Str;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationGroup = 'Realização';
    protected static ?int $navigationSort = 22;
    protected static ?string $navigationLabel = 'Relatórios';
    protected static ?string $modelLabel = 'Relatório';
    protected static ?string $pluralModelLabel = 'Relatórios';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static array $config;

    public static function form(Form $form): Form
    {
        // $formDefault = [
        //     Hidden::make('uuid')
        //         ->default(md5(Str::orderedUuid())),
        // ];

        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Hidden::make('id'),
                        TextInput::make('titulo')
                            ->label('Título')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Section::make('Conteúdo do relatório')
                            ->collapsible()
                            ->schema([
                                ComponentsBuilder::make('conteudo')
                                    ->cloneable()
                                    ->label('')
                                    ->blockNumbers(false)
                                    ->reorderableWithButtons()
                                    ->collapsible()
                                    ->collapsed()
                                    ->cloneable()
                                    ->addActionLabel('Adicionar conteúdo')
                                    ->blocks([
                                        ComponentsBuilder\Block::make('blocoTexto')
                                            ->label('Bloco')
                                            ->icon('heroicon-o-bars-3')
                                            ->schema([
                                                // ...$formDefault,
                                                TextInput::make('blocoTextoTitulo')
                                                    ->label('Título do formulário')
                                                    ->required(),
                                                RichEditor::make('blocoTextoConteudo')
                                                    ->label('Escreva o conteúdo do bloco')
                                                    ->disableToolbarButtons([
                                                        // 'attachFiles',
                                                        // 'link',
                                                    ]),
                                            ]),
                                        ComponentsBuilder\Block::make('blocoForms')
                                            ->label('Formulário')
                                            ->icon('heroicon-o-bars-3')
                                            ->schema([
                                                // ...$formDefault,
                                                TextInput::make('blocoFormsTitulo')
                                                    ->label('Título do bloco')
                                                    ->required(),
                                                Select::make('blocoFormsForm')
                                                    ->label('Selecione o formulário')
                                                    ->reactive()
                                                    ->searchable()
                                                    ->required()
                                                    ->options(
                                                        fn () => AuditForm::orderBy('titulo')->pluck('titulo', 'id')
                                                    ),
                                                Select::make('blocoFormsPlans')
                                                    ->label('Selecione as fiscalizações')
                                                    ->multiple()
                                                    ->reactive()
                                                    ->required()
                                                    ->options(
                                                        fn (\Filament\Forms\Get $get) => AuditFormList::where('audit_form_id', $get('blocoFormsForm'))->where('respondido', true)->pluck('titulo', 'id')
                                                    ),
                                                Toggle::make('insertNonConformities')
                                                    ->label('Inserir não conformidades e recomendações?'),
                                            ])
                                    ])
                            ]),
                        Section::make('Configurações do relatório')
                            ->collapsible()
                            ->collapsed()
                            ->schema([
                                Textarea::make('subTitulo')
                                    ->label('Subtítulo da capa')
                                    ->afterStateHydrated(function (Component $component, \Filament\Forms\Get $get) {
                                        $component->state($get('config')['subTitulo'] ?? null);
                                    }),
                                TextInput::make('localData')
                                    ->label('Local e data da capa')
                                    ->afterStateHydrated(function (Component $component, \Filament\Forms\Get $get) {
                                        $component->state($get('config')['localData'] ?? null);
                                    }),
                                Textarea::make('rodape')
                                ->label('Rodapé do arquivo (máximo 2 linhas)')
                                ->rows(2)
                                ->afterStateHydrated(function (Component $component, \Filament\Forms\Get $get) {
                                    $component->state($get('config')['rodape'] ?? null);
                                }),
                                Toggle::make('logoAgencia')
                                    ->label('Inserir a logo da agência?')
                                    ->afterStateHydrated(function (Component $component, \Filament\Forms\Get $get) {
                                        $component->state($get('config')['logoAgencia'] ?? null);
                                    }),
                            ])
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
                TextColumn::make('titulo')
                    ->label('Título')
                    ->searchable()
                    ->sortable()
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                \App\Filament\Actions\GerarRelatorioAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListReports::route('/'),
            'create' => Pages\CreateReport::route('/create'),
            'edit' => Pages\EditReport::route('/{record}/edit'),
            'view' => Pages\ViewReport::route('/{record}'),
        ];
    }
}
