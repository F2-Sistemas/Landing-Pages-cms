<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditFormResource\Pages;
use App\Models\AuditForm;
use App\Models\FormOption;
use App\Models\FormOptionList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Filament\Forms\Components\Builder as ComponentsBuilder;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Model;

class AuditFormResource extends Resource
{
    protected static ?string $model = AuditForm::class;

    protected static ?string $navigationGroup = 'Cadastro';
    protected static ?int $navigationSort = 13;
    protected static ?string $navigationLabel = 'Formulários';
    protected static ?string $modelLabel = 'Formulário';
    protected static ?string $pluralModelLabel = 'Formulários';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    protected static ?string $recordTitleAttribute = 'titulo';

    // public static function getGloballySearchableAttributes(): array
    // {
    //     return ['titulo', 'descricao'];
    // }

    // public static function getGlobalSearchResultDetails(Model $record): array
    // {
    //     return [
    //         'Título:' => $record->titulo,
    //         'Descrição' => $record->descricao,
    //     ];
    // }

    public static function form(Form $form): Form
    {
        $formObrigatorio = [
            Toggle::make('obrigatorio')
                ->label('Obrigatório?')
                ->columnSpan(1)
                ->onColor('success'),
        ];
        $formDefault = [
            Hidden::make('uuid')
                ->default(md5(Str::orderedUuid())),
        ];

        $formReferencia = [
            // Select::make('referencia')
            //     ->label('Selecione a pergunta Pai')
            //     // ->default('nenhum')
            //     ->reactive()
            //     // ->required()
            //     ->options(
            //         function (\Filament\Forms\Get $get, $livewire) {
            //             $dados = [
            //                 // 'nenhum'=>'Nenhum',
            //             ];
            //             $numeroPergunta = 0;

            //             foreach($livewire->data['conteudo'] as $key=>$value) {
            //                 $numeroPergunta++;
            //                 if ($value['data']['uuid']==$get('uuid') || !in_array($value['data']['tipo_pergunta'], ['1', '2']))
            //                 {
            //                     continue;
            //                 }

            //                 if (is_string($value['data']['uuid'])) {
            //                     $dados[$value['data']['uuid']]=$value['data']['pergunta'] ?? 'Pergunta numero ' . $numeroPergunta;
            //                 }
            //             }
            //             return $dados;
            //         }
            //     ),
            // Select::make('valor')
            //     ->label('Valor')
            //     ->hidden(function (\Filament\Forms\Get $get) {
            //         return empty($get('referencia'));
            //     })
            //     ->reactive()
            //     ->required()
            //     ->options(
            //         function (\Filament\Forms\Get $get, $livewire) {
            //             $dados = [];

            //             $referencia = $get('referencia');
            //             // \Log::info(print_r($livewire->data['conteudo'], true));

            //             foreach($livewire->data['conteudo'] as $key=>$value) {
            //                 if ($value['data']['uuid']==$referencia)
            //                 {
            //                     $dados = FormOptionList::where('form_option_id', $value['data']['opcoes'])->pluck('opcao', 'id');
            //                 }
            //             }
            //             return $dados;
            //         }
            //     )
        ];

        return $form
            ->schema([
                Section::make()
                ->schema([
                    TextInput::make('titulo')
                        ->label('Título')
                        ->required()
                        ->unique(ignoreRecord:true),
                    Textarea::make('descricao')
                        ->label('Descrição')
                        ->nullable(),
                    ComponentsBuilder::make('conteudo')
                        ->label('Conteúdo')
                        ->cloneable()
                        ->blockNumbers(false)
                        ->blocks([
                            ComponentsBuilder\Block::make('bloco')
                                ->label('Bloco')
                                ->icon('heroicon-o-bars-3')
                                ->schema([
                                    ...$formDefault,
                                    TextInput::make('blocoTitulo')
                                        ->label('Título do bloco')
                                        ->required(),
                                ]),
                            ComponentsBuilder\Block::make('multiplaEscolha')
                                ->label('Multipla escolha')
                                ->icon('heroicon-o-bars-3')
                                ->columns(2)
                                ->schema([
                                    Hidden::make('tipo_pergunta')
                                        ->default('1'),
                                    ...$formDefault,
                                    ...$formObrigatorio,
                                    Toggle::make('MErespostaUnica')
                                        ->label('Reesposta única?')
                                        ->default(true)
                                        ->reactive()
                                        ->columnSpan(1),
                                    Toggle::make('MEimagem')
                                        ->label('Solicitar imagem')
                                        ->reactive()
                                        // ->afterStateHydrated(function (Component $component, $get) {
                                        //     if ($get('respostaUnica')) {
                                        //         $component->state(false);
                                        //     } else {
                                        //         $component->state(true);
                                        //     }
                                        //     // $component->state(intval(static::$answers[$uuid]['resposta'] ?? null));
                                        // })
                                        ->hidden(function (\Filament\Forms\Get $get) {
                                            if ($get('MErespostaUnica')) {
                                                return false;
                                            } else {
                                                return true;
                                            }
                                        })
                                        ->disabled(function (\Filament\Forms\Get $get) {
                                            if ($get('MErespostaUnica')) {
                                                return false;
                                            } else {
                                                return true;
                                            }
                                        })
                                        ->columnSpan(1),
                                    Toggle::make('MEjustificativa')
                                        ->label('Solicitar justificativa')
                                        ->hidden(function (\Filament\Forms\Get $get) {
                                            if ($get('MErespostaUnica')) {
                                                return false;
                                            } else {
                                                return true;
                                            }
                                        })
                                        ->disabled(function (\Filament\Forms\Get $get) {
                                            if ($get('MErespostaUnica')) {
                                                return false;
                                            } else {
                                                return true;
                                            }
                                        })
                                        ->columnSpan(1),
                                    TextInput::make('pergunta')
                                        ->label('Digite aqui a pergunta')
                                        ->columnSpan(2)
                                        ->required(),
                                    Select::make('opcoes')
                                        ->label('Opções de resposta')
                                        ->columnSpan(2)
                                        ->required()
                                        ->options(
                                            function (\Filament\Forms\Get $get) {
                                                $options = FormOption::pluck('nome', 'id')->toArray();

                                                if (!$get('MErespostaUnica')) {
                                                    $optionLists = FormOptionList::get()->toArray();

                                                    foreach ($optionLists as $value) {
                                                        if ($value['conforme'] > 0) {
                                                            unset($options[$value['form_option_id']]);
                                                        }
                                                    }
                                                }

                                                return $options;
                                            }
                                        ),
                                    Section::make('Padrão para encaminhamento das não conformidades:')
                                        ->collapsible()
                                        ->collapsed()
                                        ->hidden(function (\Filament\Forms\Get $get) {
                                            if ($get('MErespostaUnica')) {
                                                return false;
                                            } else {
                                                return true;
                                            }
                                        })
                                        ->schema([
                                            TextInput::make('referencia')
                                                ->label('Referência da não conformidade')
                                                ->nullable(),
                                            TextArea::make('recomendacao')
                                                ->label('Recomendação')
                                                ->autosize()
                                                ->rows(2)
                                                ->nullable(),
                                            TextInput::make('prazo')
                                                ->label('Prazo para tratar a não conformidade (dias corridos)')
                                                ->integer()
                                                ->nullable(),
                                        ]),
                                    ...$formReferencia
                                ]),
                            ComponentsBuilder\Block::make('multiplaEscolhaOpcoes')
                                ->label('Multipla escolha - digitar opções')
                                ->icon('heroicon-o-bars-3')
                                ->columns(2)
                                ->schema([
                                    Hidden::make('tipo_pergunta')
                                        ->default('1'),
                                    ...$formDefault,
                                    ...$formObrigatorio,
                                    Toggle::make('MEOrespostaUnica')
                                        ->label('Reesposta única?')
                                        ->default(true)
                                        ->reactive()
                                        ->columnSpan(1),
                                    TextInput::make('pergunta')
                                        ->label('Digite aqui a pergunta')
                                        ->columnSpan(2)
                                        ->required(),
                                    TagsInput::make('opcoes')
                                        ->label('Opções de respostas')
                                        ->columnSpan(2)
                                        ->placeholder('Digite uma nova opção'),

                                    ...$formReferencia
                                ]),
                            ComponentsBuilder\Block::make('respostaCurta')
                                ->label('Resposta curta')
                                ->icon('heroicon-o-bars-3')
                                ->schema([
                                    Hidden::make('tipo_pergunta')
                                        ->default('3'),
                                    ...$formDefault,
                                    ...$formObrigatorio,
                                    TextInput::make('pergunta')
                                        ->label('Digite aqui a pergunta')
                                        ->required(),
                                    ...$formReferencia
                                ]),
                            ComponentsBuilder\Block::make('respostaParagrafo')
                                ->label('Parágrafo')
                                ->icon('heroicon-o-bars-3')
                                ->schema([
                                    Hidden::make('tipo_pergunta')
                                        ->default('4'),
                                    ...$formDefault,
                                    ...$formObrigatorio,
                                    TextInput::make('pergunta')
                                        ->label('Digite aqui a pergunta')
                                        ->required(),
                                    ...$formReferencia
                                ]),
                            ComponentsBuilder\Block::make('data')
                                ->label('Data')
                                ->icon('heroicon-o-bars-3')
                                ->schema([
                                    Hidden::make('tipo_pergunta')
                                        ->default('5'),
                                    ...$formDefault,
                                    ...$formObrigatorio,
                                    TextInput::make('pergunta')
                                        ->label('Digite aqui a pergunta')
                                        ->required(),
                                    // Repeater::make('qualifications')
                                    //     ->schema([
                                    //         TextInput::make('name')->required(),
                                    //     ])
                                    //     ->cloneable(),
                                    ...$formReferencia
                                ]),
                            ComponentsBuilder\Block::make('hora')
                                ->label('Hora')
                                ->icon('heroicon-o-bars-3')
                                ->schema([
                                    Hidden::make('tipo_pergunta')
                                        ->default('6'),
                                    ...$formDefault,
                                    ...$formObrigatorio,
                                    TextInput::make('pergunta')
                                        ->label('Digite aqui a pergunta')
                                        ->required(),
                                    ...$formReferencia
                                ]),
                            ComponentsBuilder\Block::make('dataHora')
                                ->label('Data e hora')
                                ->icon('heroicon-o-bars-3')
                                ->schema([
                                    Hidden::make('tipo_pergunta')
                                        ->default('7'),
                                    ...$formDefault,
                                    ...$formObrigatorio,
                                    TextInput::make('pergunta')
                                        ->label('Digite aqui a pergunta')
                                        ->required(),
                                    ...$formReferencia
                                ]),
                            ComponentsBuilder\Block::make('numero')
                                ->label('Número')
                                ->icon('heroicon-o-bars-3')
                                ->schema([
                                    Hidden::make('tipo_pergunta')
                                        ->default('10'),
                                    ...$formDefault,
                                    ...$formObrigatorio,
                                    TextInput::make('pergunta')
                                        ->label('Digite aqui a pergunta')
                                        ->required(),
                                    TextInput::make('casas_decimais')
                                        ->label('Casas Decimais')
                                        ->integer()
                                        ->minValue(0)
                                        ->required(),
                                    ...$formReferencia
                                ]),
                            ComponentsBuilder\Block::make('arquivo')
                                ->label('Carregamento de arquivo')
                                ->icon('heroicon-o-bars-3')
                                ->schema([
                                    Hidden::make('tipo_pergunta')
                                        ->default('8'),
                                    TextInput::make('pergunta')
                                        ->label('Digite aqui a pergunta')
                                        ->required(),
                                    ...$formDefault,
                                    ...$formObrigatorio,
                                    Toggle::make('multiplos')
                                        ->label('Permitir multiplos arquivos?')
                                        ->reactive()
                                        ->onColor('success'),
                                    Select::make('solicitar_label')
                                        ->label('Solicitar descrição do(s) arquivo(s)?')
                                        ->reactive()
                                        ->options(function (\Filament\Forms\Get $get) {
                                            if ($get('multiplos')) {
                                                $respostas = ['geral' => 'Solicitar unico descritivo para todos os arquivos', 'individual' => 'Solicitar um descritivo para cada arquivo'];
                                            } else {
                                                $respostas = ['geral' => 'Solicitar descritivo para o arquivo'];
                                            }

                                            return $respostas;
                                        })
                                    ,
                                    ...$formReferencia
                                ]),
                            ComponentsBuilder\Block::make('foto')
                                ->label('Carregamento de imagem')
                                ->icon('heroicon-o-bars-3')
                                ->schema([
                                    Hidden::make('tipo_pergunta')
                                        ->default('9'),
                                    TextInput::make('pergunta')
                                        ->label('Digite aqui a pergunta')
                                        ->required(),
                                    ...$formDefault,
                                    ...$formObrigatorio,
                                    Toggle::make('multiplos')
                                        ->label('Permitir multiplas imagens?')
                                        ->onColor('success')
                                        ->reactive(),
                                    Select::make('solicitar_label')
                                        ->label('Solicitar descrição da(s) imagen(s)?')
                                            // ->options([
                                            //     'geral' => 'Solicitar descritivo para a imagem',
                                            //     'individual' => 'Solicitar um descritivo para cada imagem (no caso de multiplas)',
                                            // ])
                                            // ->hidden(fn ($get) : bool => $get('multiplos'))
                                            ->reactive()
                                            ->options(function (\Filament\Forms\Get $get) {
                                                if ($get('multiplos')) {
                                                    $respostas = ['geral' => 'Solicitar unico descritivo para todas as imagens', 'individual' => 'Solicitar um descritivo para cada imagem'];
                                                } else {
                                                    $respostas = ['geral' => 'Solicitar descritivo para a imagem'];
                                                }

                                                return $respostas;
                                            })
                                    ,
                                    ...$formReferencia
                                ]),
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
                TextColumn::make('titulo')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('descricao')
                    ->label('Descrição')
                    ->limit(20)
                    ->markdown()
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
            'index' => Pages\ListAuditForms::route('/'),
            'create' => Pages\CreateAuditForm::route('/create'),
            'edit' => Pages\EditAuditForm::route('/{record}/edit'),
        ];
    }
}
