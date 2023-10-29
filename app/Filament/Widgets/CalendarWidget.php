<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\AnswerResource;
use App\Models\AuditForm;
use App\Models\AuditFormList;
use App\Models\ContentTree;
use App\Models\Tree;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
// use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Actions\CreateAction;
use Saade\FilamentFullCalendar\Actions\DeleteAction;
use Saade\FilamentFullCalendar\Actions\EditAction;
use Saade\FilamentFullCalendar\Actions\viewAction;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    public Model|string|null $model = AuditFormList::class;

    public static function canView(): bool
    {
        return tenancy()->initialized;
    }

    protected function headerActions(): array
    {
        return [
            CreateAction::make()->label('Nova Fiscalização'),
        ];
    }

    // Coloca o botao de incluir no topo - Entra ao atualizar a pagina
    protected function modalActions(): array
    {
        return [
            CreateAction::make()
                ->modalHeading('Nova Fiscalização'),

            EditAction::make(),
            // DeleteAction::make(),
        ];
    }

    // protected function viewAction(): Action
    // {
    //     return ViewAction::make();
    // }

    public function fetchEvents(array $fetchInfo): array
    {
        if (! tenancy()->initialized) {
            return [];
        }

        return AuditFormList::query()
        // ->where('starts_at', '>=', $fetchInfo['start'])
        // ->where('ends_at', '<=', $fetchInfo['end'])
            ->get()
            ->map(
                function (AuditFormList $event) {
                    $hoje = new \DateTime();
                    $hoje = new \DateTime($hoje->format('Y-m-d'));
                    $inicioData = new \DateTime($event->data_inicio);

                    $cor = null;

                    if ($event->respondido) {
                        // Cor verde
                        $cor = '#006400';
                    } else {
                        if ($inicioData < $hoje) {
                            // Cor vermelha
                            $cor = '#A52A2A';
                        } else {
                            // Cor Azul
                            $cor = '#191970';
                        }
                    }
                    $terminoData = new \DateTime($event->data_termino);
                    date_add($terminoData, date_interval_create_from_date_string('1 days'));
                    $terminoData = $terminoData->format('Y-m-d');

                    return
                    [
                        'id' => $event->id,
                        'title' => $event->titulo,
                        'start' => $event->data_inicio,
                        'end' => $terminoData,
                        'url' => AnswerResource::getUrl(
                            name: 'edit',
                            parameters: [
                                'record' => $event,
                            ]
                        ),
                        'shouldOpenUrlInNewTab' => false,
                        'color' => $cor,
                    ];
                }
            )
            ->all();
    }

    // Abre ao arrastar
    public function onEventDrop(array $event, array $oldEvent, array $relatedEvents, array $delta): bool
    {
        $inicio = $event['start'] ?? null;
        $termino = new \DateTime($event['end'] ?? $inicio);
        date_add($termino, date_interval_create_from_date_string('-1 days'));
        $termino = $termino->format('Y-m-d');

        $updateData = AuditFormList::where('id', $event['id'])->update([
            'data_inicio' => $inicio,
            'data_termino' => $termino,
        ]);

        return false;
    }

    public function onEventResize(array $event, array $oldEvent, array $relatedEvents, array $startDelta, array $endDelta): bool
    {
        $inicio = $event['start'] ?? null;
        $termino = new \DateTime($event['end'] ?? $inicio);
        date_add($termino, date_interval_create_from_date_string('-1 days'));
        $termino = $termino->format('Y-m-d');

        $updateData = AuditFormList::where('id', $event['id'])->update([
            'data_inicio' => $inicio,
            'data_termino' => $termino,
        ]);

        return false;
    }

    // Entra ao clicar para incluir um novo planejamento
    public function getFormSchema(): array
    {
        if (! tenancy()->initialized) {
            return [
                Section::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('no_inited')
                            ->label('Esse formulário só pode ser acessado por um usuário de agência.')
                            ->disabled()
                            ->columnSpanFull(),
                    ]),
            ];
        }

        return [
            Section::make()
                ->columns(2)
                ->schema([
                    TextInput::make('titulo')
                        ->label('Título')
                        ->columnSpan(2)
                        ->required(),

                    // Falta conseguir pegar qual o codigo da árvore
                    Select::make('tree_id')
                        ->label('Selecione a árvore:')
                        ->columnSpan(1)
                        ->required()
                        ->reactive()
                        ->options(Tree::pluck('nome', 'id')),

                    Select::make('content_tree_id')
                        ->label('Onde será aplicado?')
                        ->columnSpan(1)
                        ->reactive()
                        ->hidden(fn (\Filament\Forms\Get $get) => empty($get('tree_id')))
                        ->options(
                            fn (\Filament\Forms\Get $get) => ContentTree::where(
                                'tree_id',
                                $get('tree_id'),
                            )->pluck('nome', 'id')
                        ),

                    Select::make('audit_form_id')
                        ->label('Selecione o formulário:')
                        ->columnSpan(2)
                        ->required()
                        ->options(AuditForm::pluck('titulo', 'id')),

                    DatePicker::make('data_inicio')
                        ->default(now())
                        ->columnSpan(1)
                        ->reactive()
                        ->afterStateUpdated(fn (Set $set, ?string $state) => $set('data_termino', $state)),

                    DatePicker::make('data_termino')
                        ->columnSpan(1)
                        ->reactive()
                        ->minDate(fn (\Filament\Forms\Get $get) => $get('data_inicio'))
                        ->default(now()),
                ]),
        ];
    }
}
