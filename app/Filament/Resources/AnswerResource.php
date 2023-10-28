<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnswerResource\Pages;
use App\Models\Answer;
use App\Models\AuditForm;
use App\Models\AuditFormList;
use App\Models\FormOptionList;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class AnswerResource extends Resource
{
    protected static ?string $model = AuditFormList::class;

    protected static ?string $navigationGroup = 'NÃO MOSTRAR';
    protected static ?int $navigationSort = 50;
    protected static ?string $navigationLabel = 'Responder Fiscalização';
    protected static ?string $modelLabel = 'Resposta de fiscalização';
    protected static ?string $pluralModelLabel = 'Respostas de fiscalização';
    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?string $slug = 'resposta';

    public static array $answers;
    public static array $types;
    public static $audit_form_list_id = null;
    public static $blocosTemp = [];
    public static $naoConforme = null;

    public static function shouldRegisterNavigation(): bool
    {
        return false; // 'false' oculta o item no menu
    }

    public static function form(Form $form): Form
    {
        self::$audit_form_list_id = $form->getRecord()->id;

        $formulario = AuditFormList::query()
            ->where('id', self::$audit_form_list_id)
            ->first();

        $answers = null;
        $answers = Answer::query()
            ->where('audit_form_list_id', self::$audit_form_list_id)
            ->where('user_id', auth()->user()->id)
            ->get();

        $questionarios = AuditForm::query()
            ->where('id', $formulario->audit_form_id)
            ->first();

        return $form
            ->schema([
                Section::make($questionarios?->titulo)
                    ->description(nl2br($questionarios?->descricao))
                    // ->markdown()
                    ->schema([
                        Hidden::make('audit_form_list_id')
                            ->default(self::$audit_form_list_id)
                            ->afterStateHydrated(function (Component $component) {
                                $component->state(self::$audit_form_list_id);
                            }),
                        ...self::montarQuestionarios($questionarios->conteudo, $answers)
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->emptyStateActions([
                // Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnswers::route('/'),
            // 'create' => Pages\CreateAnswer::route('/create'),
            'edit' => Pages\EditAnswer::route('/{record}/edit'),
        ];
    }

    public static function formatAnswers($answers = null)
    {
        if (empty($answers)) {
            return null;
        }
        $out = [];

        foreach ($answers as $answer) {
            if (in_array($answer->resposta_type, ['arquivo', 'foto'])) {
                $out[str_replace('-', '', $answer->pergunta_uuid)]['resposta'] = json_decode($answer->resposta, true);
            } else {
                $temp = (json_decode($answer->resposta, true) ?? $answer->resposta);
                $out[str_replace('-', '', $answer->pergunta_uuid)]['resposta'] = $temp;
            }

            if ($answer->resposta_type == 'multiplaEscolha') {
                $out[str_replace('-', '', $answer->pergunta_uuid)]['comentario'] = $answer?->comentario;
                $temp = $answer?->images;
                $out[str_replace('-', '', $answer->pergunta_uuid)]['images'] = $temp;
            }
        }

        return $out;
    }

    public static function inserirObrigatorio($obrigatorio)
    {
        if (!$obrigatorio) {
            return '';
        }

        return ' *';
    }

    public static function inserirQuestao($questao = null)
    {
        $retorno = [];

        if ($questao['type'] == 'multiplaEscolha') {
            $teste = FormOptionList::query()->where('form_option_id', $questao['data']['opcoes'])->get();
            $opcoes = $teste->pluck('opcao', 'id')->toArray();
            $imagem = [];
            $justificativa = [];

            if ($questao['data']['MErespostaUnica']) {
                $conforme = $teste->where('conforme', 2)->pluck('conforme')->toArray();
                static::$naoConforme = $conforme[0] ?? null;
                $checkList = [Radio::make($questao['data']['uuid'])
                                ->label('')
                                ->required($questao['data']['obrigatorio'])
                                ->options($opcoes)
                                ->reactive()
                                ->afterStateHydrated(function (Component $component) {
                                    $uuid = str_replace('data.', '', $component->getId());
                                    $component->state(intval(static::$answers[$uuid]['resposta'] ?? null));
                                })];

                if ($questao['data']['MEimagem']) {
                    $imagem = [
                        Repeater::make($questao['data']['uuid'] . '_images')
                            ->label('Imagens')
                            ->addActionLabel('Adicionar imagem')
                            ->hidden(function (Component $component, Get $get) {
                                $uuid = str_replace('_images', '', str_replace('data.', '', $component->getId()));

                                if ($get($uuid) == static::$naoConforme) {
                                    return false;
                                }

                                return true;
                            })
                            ->schema([
                                FileUpload::make('imagem')
                                    ->label('')
                                    ->directory('forms')
                                    ->image()
                                    ->required(true),
                                TextInput::make("label")
                                    ->label('Informar a descrição da imagem')
                            ])
                            ->afterStateHydrated(function (Component $component) {
                                $uuid = str_replace('_images', '', str_replace('data.', '', $component->getId()));
                                $out = [];
                                $arquivos = static::$answers[$uuid]['images'] ?? [];

                                if (is_array($arquivos)) {
                                    foreach ($arquivos as $value) {
                                        $out[] = [
                                            'imagem' => [$value['imagem']],
                                            'label' => $value['label']
                                        ];
                                    }
                                }
                                $component->state($out);
                            })
                            ->defaultItems(count($answers[$questao['data']['uuid']]['images'] ?? []))
                    ];
                }

                if ($questao['data']['MEjustificativa']) {
                    $justificativa = [TextInput::make($questao['data']['uuid'] . "_comentario")
                    ->label('')
                    ->placeholder('Comentários')
                    ->hidden(function (Component $component, Get $get) {
                        $uuid = str_replace('_comentario', '', str_replace('data.', '', $component->getId()));

                        if ($get($uuid) == static::$naoConforme) {
                            return false;
                        }

                        return true;
                    })
                    ->afterStateHydrated(function (Component $component) {
                        $uuid = str_replace('_comentario', '', str_replace('data.', '', $component->getId()));
                        $component->state(static::$answers[$uuid]['comentario'] ?? null);
                    })];
                }
            } else {
                $checkList = [CheckboxList::make($questao['data']['uuid'])
                            ->label('')
                            ->required($questao['data']['obrigatorio'])
                            ->options($opcoes)
                            ->afterStateHydrated(function (Component $component) {
                                $uuid = str_replace('data.', '', $component->getId());
                                $resposta = [];
                                $resposta = static::$answers[$uuid]['resposta'] ?? [];

                                // if (!empty($resposta)) {
                                //     $resposta = json_decode($resposta, true);
                                // }
                                if (!is_array($resposta)) {
                                    $resposta = [];
                                }
                                $component->state($resposta);
                            })
                ];
            }
            $retorno = Section::make($questao['data']['pergunta'] . self::inserirObrigatorio($questao['data']['obrigatorio']))
                ->schema([
                    ...$checkList,
                    ...$justificativa,
                    ...$imagem,
                    // CheckboxList::make($questao['data']['uuid'])
                    //     ->label('')
                    //     ->required($questao['data']['obrigatorio'])
                    //     ->options($opcoes)
                    //     ->afterStateHydrated(function (Component $component) {
                    //         $uuid = str_replace('data.', '', $component->getId());
                    //         $component->state(intval(static::$answers[$uuid]['resposta'] ?? null));
                    //     }),
                ]);
        }

        if ($questao['type'] == 'multiplaEscolhaOpcoes') {
            $opcoes = $questao['data']['opcoes'];

            if ($questao['data']['MEOrespostaUnica']) {
                $checkList = [Radio::make($questao['data']['uuid'])
                    ->label('')
                    ->required($questao['data']['obrigatorio'])
                    ->options($opcoes)
                    ->afterStateHydrated(function (Component $component) {
                        $uuid = str_replace('data.', '', $component->getId());
                        $component->state(static::$answers[$uuid]['resposta'] ?? null);
                    })];
            } else {
                $checkList = [CheckboxList::make($questao['data']['uuid'])
                    ->label('')
                    ->required($questao['data']['obrigatorio'])
                    ->options($opcoes)
                    ->afterStateHydrated(function (Component $component) {
                        $uuid = str_replace('data.', '', $component->getId());
                        $resposta = [];
                        $resposta = static::$answers[$uuid]['resposta'] ?? [];

                        // if (!empty($resposta)) {
                        //     $resposta = json_decode($resposta, true);
                        // }
                        if (!is_array($resposta)) {
                            $resposta = [];
                        }
                        $component->state($resposta);
                    })
                ];
            }

            $retorno = Section::make($questao['data']['pergunta'] . self::inserirObrigatorio($questao['data']['obrigatorio']))
                ->schema([
                    ...$checkList,
                ]);
        }

        if ($questao['type'] == 'respostaCurta') {
            $retorno = Section::make($questao['data']['pergunta'] . self::inserirObrigatorio($questao['data']['obrigatorio']))
                ->schema([
                    TextInput::make($questao['data']['uuid'])
                        ->label('')
                        ->required($questao['data']['obrigatorio'])
                        ->afterStateHydrated(function (Component $component) {
                            $uuid = str_replace('data.', '', $component->getId());
                            $component->state(static::$answers[$uuid]['resposta'] ?? null);
                        }),
                ]);
        }

        if ($questao['type'] == 'respostaParagrafo') {
            $retorno = Section::make($questao['data']['pergunta'] . self::inserirObrigatorio($questao['data']['obrigatorio']))
                ->schema([
                    Textarea::make($questao['data']['uuid'])
                        ->label('')
                        ->required($questao['data']['obrigatorio'])
                        ->autosize()
                        ->rows(3)
                        ->afterStateHydrated(function (Component $component) {
                            $uuid = str_replace('data.', '', $component->getId());
                            $component->state(static::$answers[$uuid]['resposta'] ?? null);
                        }),
                ]);
        }

        if ($questao['type'] == 'data') {
            $retorno = Section::make($questao['data']['pergunta'] . self::inserirObrigatorio($questao['data']['obrigatorio']))
                ->schema([
                    DatePicker::make($questao['data']['uuid'])
                        ->label('')
                        ->required($questao['data']['obrigatorio'])
                        ->nullable()
                        // ->format('d/m/Y')
                        ->afterStateHydrated(function (Component $component) {
                            $uuid = str_replace('data.', '', $component->getId());
                            $component->state(static::$answers[$uuid]['resposta'] ?? null);
                        }),
                ]);
        }

        if ($questao['type'] == 'hora') {
            $retorno = Section::make($questao['data']['pergunta'] . self::inserirObrigatorio($questao['data']['obrigatorio']))
                ->schema([
                    TimePicker::make($questao['data']['uuid'])
                        ->label('')
                        ->required($questao['data']['obrigatorio'])
                        ->seconds(false)
                        ->afterStateHydrated(function (Component $component) {
                            $uuid = str_replace('data.', '', $component->getId());
                            $component->state(static::$answers[$uuid]['resposta'] ?? null);
                        }),
                ]);
        }

        if ($questao['type'] == 'dataHora') {
            $retorno = Section::make($questao['data']['pergunta'] . self::inserirObrigatorio($questao['data']['obrigatorio']))
                ->schema([
                    DateTimePicker::make($questao['data']['uuid'])
                        ->label('')
                        ->required($questao['data']['obrigatorio'])
                        ->seconds(false)
                        ->afterStateHydrated(function (Component $component) {
                            $uuid = str_replace('data.', '', $component->getId());
                            $component->state(static::$answers[$uuid]['resposta'] ?? null);
                        }),
                ]);
        }

        if ($questao['type'] == 'numero') {
            $retorno = Section::make($questao['data']['pergunta'] . self::inserirObrigatorio($questao['data']['obrigatorio']))
                ->schema([
                    TextInput::make($questao['data']['uuid'])
                        ->label('')
                        ->required($questao['data']['obrigatorio'])
                        ->numeric()
                        // Ajuda aqui
                        // - [ ] Formatação de número nao funciona no Answer resource
                        // Após funcionar o formato precisa alterar o campo de casaas_decimais para: $questao['data']['casas_decimais']
                        // ->mask(RawJs::make(<<<'JS'
                        //     $money($input, '.', '', 2)
                        // JS))
                        ->afterStateHydrated(function (Component $component) {
                            $uuid = str_replace('data.', '', $component->getId());
                            $component->state(static::$answers[$uuid]['resposta'] ?? null);
                        }),
                ]);
        }

        if ($questao['type'] == 'arquivo') {
            if ($questao['data']['multiplos'] && $questao['data']['solicitar_label'] == 'individual') {
                $retorno = Section::make($questao['data']['pergunta'] . self::inserirObrigatorio($questao['data']['obrigatorio']))
                    ->schema([
                        Repeater::make($questao['data']['uuid'])
                            ->label('Arquivos')
                            ->schema([
                                FileUpload::make('arquivo')
                                    ->label('')
                                    ->directory('forms')
                                    ->required($questao['data']['obrigatorio']),
                                TextInput::make("label")
                                    ->label('Informar a descrição do arquivo')
                            ])
                            ->afterStateHydrated(function (Component $component) {
                                $uuid = str_replace('_label', '', str_replace('data.', '', $component->getId()));
                                $out = [];
                                $arquivos = static::$answers[$uuid]['resposta'] ?? [];

                                if (is_array($arquivos)) {
                                    foreach ($arquivos as $value) {
                                        $out[] = [
                                            'arquivo' => [$value['arquivo']],
                                            'label' => $value['label']
                                        ];
                                    }
                                }
                                $component->state($out);
                            })
                            ->defaultItems(count($answers[$questao['data']['uuid']] ?? []))
                    ]);
            } else {
                if ($questao['data']['solicitar_label'] == 'geral') {
                    $retorno = Section::make($questao['data']['pergunta'] . self::inserirObrigatorio($questao['data']['obrigatorio']))
                        ->schema([
                            FileUpload::make($questao['data']['uuid'])
                                ->label('')
                                ->directory('forms')
                                ->required($questao['data']['obrigatorio'])
                                ->multiple($questao['data']['multiplos'])
                                ->afterStateHydrated(function (Component $component) {
                                    $uuid = str_replace('_label', '', str_replace('data.', '', $component->getId()));
                                    $arquivos = static::$answers[$uuid]['resposta'] ?? [];
                                    $out = [];

                                    // \Log::info('2 - ' . $uuid . ' - ' . print_r($arquivos,true));
                                    if (is_array($arquivos)) {
                                        foreach ($arquivos as $value) {
                                            $out[] = $value['arquivo'];
                                        }
                                    } else {
                                        $out[] = $arquivos;
                                    }
                                    $component->state($out ?? null);
                                }),
                            TextInput::make($questao['data']['uuid'] . "_label")
                                ->label('Informar a descrição do arquivo')
                                ->afterStateHydrated(function (Component $component) {
                                    $uuid = str_replace('_label', '', str_replace('data.', '', $component->getId()));
                                    $component->state(static::$answers[$uuid]['resposta'][0]['label'] ?? null);
                                })
                        ]);
                } else {
                    $retorno = Section::make($questao['data']['pergunta'] . self::inserirObrigatorio($questao['data']['obrigatorio']))
                        ->schema([
                            FileUpload::make($questao['data']['uuid'])
                                ->label('')
                                ->directory('forms')
                                ->required($questao['data']['obrigatorio'])
                                ->multiple($questao['data']['multiplos'])
                                ->afterStateHydrated(function (Component $component) {
                                    $uuid = str_replace('_label', '', str_replace('data.', '', $component->getId()));
                                    $arquivos = static::$answers[$uuid]['resposta'] ?? [];
                                    $out = [];

                                    // \Log::info('3 - ' . $uuid . ' - ' . print_r(empty($arquivos),true));
                                    if (empty($arquivos)) {
                                        $arquivos = [];
                                    }

                                    if (is_array($arquivos)) {
                                        foreach ($arquivos as $value) {
                                            $out[] = $value['arquivo'];
                                        }
                                    } else {
                                        $out[] = $arquivos;
                                    }
                                    $component->state($out ?? null);
                                }),
                        ]);
                }
            }
        }

        if ($questao['type'] == 'foto') {
            if ($questao['data']['multiplos'] && $questao['data']['solicitar_label'] == 'individual') {
                $retorno = Section::make($questao['data']['pergunta'] . self::inserirObrigatorio($questao['data']['obrigatorio']))
                    ->schema([
                        Repeater::make($questao['data']['uuid'])
                            ->label('Imagens')
                            ->schema([
                                FileUpload::make('arquivo')
                                    ->label('')
                                    ->directory('forms')
                                    ->image()
                                    ->required($questao['data']['obrigatorio']),
                                TextInput::make("label")
                                    ->label('Informar a descrição da imagem')
                            ])
                            ->afterStateHydrated(function (Component $component) {
                                $uuid = str_replace('_label', '', str_replace('data.', '', $component->getId()));
                                $out = [];
                                $arquivos = static::$answers[$uuid]['resposta'] ?? [];

                                if (is_array($arquivos)) {
                                    foreach ($arquivos as $value) {
                                        $out[] = [
                                            'arquivo' => [$value['arquivo']],
                                            'label' => $value['label']
                                        ];
                                    }
                                }
                                $component->state($out);
                            })
                            ->defaultItems(count($answers[$questao['data']['uuid']] ?? []))
                    ]);
            } else {
                if ($questao['data']['solicitar_label'] == 'geral') {
                    $retorno = Section::make($questao['data']['pergunta'] . self::inserirObrigatorio($questao['data']['obrigatorio']))
                        ->schema([
                            FileUpload::make($questao['data']['uuid'])
                                ->label('')
                                ->directory('forms')
                                ->required($questao['data']['obrigatorio'])
                                ->image()
                                ->multiple($questao['data']['multiplos'])
                                ->afterStateHydrated(function (Component $component) {
                                    $uuid = str_replace('_label', '', str_replace('data.', '', $component->getId()));
                                    $arquivos = static::$answers[$uuid]['resposta'] ?? [];
                                    $out = [];

                                    // \Log::info('2 - ' . $uuid . ' - ' . print_r($arquivos,true));
                                    if (is_array($arquivos)) {
                                        foreach ($arquivos as $value) {
                                            $out[] = $value['arquivo'];
                                        }
                                    } else {
                                        $out[] = $arquivos;
                                    }
                                    $component->state($out ?? null);
                                }),
                            TextInput::make($questao['data']['uuid'] . "_label")
                                ->label('Informar a descrição da imagem')
                                ->afterStateHydrated(function (Component $component) {
                                    $uuid = str_replace('_label', '', str_replace('data.', '', $component->getId()));
                                    $component->state(static::$answers[$uuid]['resposta'][0]['label'] ?? null);
                                })
                        ]);
                } else {
                    $retorno = Section::make($questao['data']['pergunta'] . self::inserirObrigatorio($questao['data']['obrigatorio']))
                        ->schema([
                            FileUpload::make($questao['data']['uuid'])
                                ->label('')
                                ->directory('forms')
                                ->required($questao['data']['obrigatorio'])
                                ->multiple($questao['data']['multiplos'])
                                ->image()
                                ->afterStateHydrated(function (Component $component) {
                                    $uuid = str_replace('_label', '', str_replace('data.', '', $component->getId()));
                                    $arquivos = static::$answers[$uuid]['resposta'] ?? [];
                                    $out = [];

                                    // \Log::info('3 - ' . $uuid . ' - ' . print_r(empty($arquivos),true));
                                    if (empty($arquivos)) {
                                        $arquivos = [];
                                    }

                                    if (is_array($arquivos)) {
                                        foreach ($arquivos as $value) {
                                            $out[] = $value['arquivo'];
                                        }
                                    } else {
                                        $out[] = $arquivos;
                                    }
                                    $component->state($out ?? null);
                                }),
                        ]);
                }
            }
        }

        return $retorno;
    }
    public static function montarQuestionarios($questionarios, $answers = null)
    {
        $retorno = [];
        static::$types = [];
        static::$answers = self::formatAnswers($answers);
        $blocos = [];
        $i = 0;

        foreach ($questionarios as $questao) {
            if ($questao['type'] == 'bloco') {
                $i++;
                $blocos[$i]['bloco'] = $questao['data'];
            } else {
                $blocos[$i]['questoes'][] = $questao;
            }
        }

        foreach ($blocos as $bloco) {
            self::$blocosTemp = $bloco;

            if (isset(self::$blocosTemp['bloco']['blocoTitulo'])) {
                // Caso o formulario tenha separacao por blocos:
                $retorno[] = Section::make(self::$blocosTemp['bloco']['blocoTitulo'])
                                ->collapsible()
                                ->schema(function () {
                                    $retorno = [];

                                    foreach (self::$blocosTemp['questoes'] as $questao) {
                                        static::$types[$questao['data']['uuid']] = $questao['type'];

                                        if ($questao['type'] != 'bloco') {
                                            $retorno[] = static::inserirQuestao($questao);
                                        }
                                    }

                                    return $retorno;
                                });
            } else {
                foreach (self::$blocosTemp['questoes'] as $questao) {
                    static::$types[$questao['data']['uuid']] = $questao['type'];

                    if ($questao['type'] != 'bloco') {
                        $retorno[] = static::inserirQuestao($questao);
                    }
                }
            }
        }
        $retorno[] = Hidden::make('types')
            ->default(static::$types)
            ->afterStateHydrated(function (Component $component) {
                $component->state(static::$types);
            });

        return $retorno;
    }
}
