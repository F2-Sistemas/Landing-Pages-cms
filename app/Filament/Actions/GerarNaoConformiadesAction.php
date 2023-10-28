<?php

namespace App\Filament\Actions;

use App\Models\Answer;
use App\Models\AuditForm;
use App\Models\FormOptionList;
use App\Models\Nonconformity;
use Closure;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Notifications\Notification;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Filament\Tables\Actions\Action;

class GerarNaoConformiadesAction extends Action
{
    use CanCustomizeProcess;

    protected ?Closure $mutateRecordDataUsing = null;

    public static function getDefaultName(): ?string
    {
        return 'gerarnaoconformidades';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Gerar Não Conformidades');

        $this->fillForm(function (Model $record, Table $table): array {
            if ($translatableContentDriver = $table->makeTranslatableContentDriver()) {
                $data = $translatableContentDriver->getRecordAttributesToArray($record);
            } else {
                $data = $record->attributesToArray();
            }

            if ($this->mutateRecordDataUsing) {
                $data = $this->evaluate($this->mutateRecordDataUsing, ['data' => $data]);
            }

            return $data;
        });

        $this->action(function (): void {
            $this->process(function (array $data, Model $record, Table $table) {
                $audit_form_list_id = $record->id;
                $perguntas = AuditForm::where('id', $record->audit_form_id)
                    ->pluck('conteudo')
                    ->toArray();
                $today = Carbon::now();
                $perguntas = $perguntas[0] ?? [];

                foreach ($perguntas as $value) {
                    if ($value['type'] == 'multiplaEscolha' && $value['data']['MErespostaUnica']) {
                        $form_option_list_id = FormOptionList::where('form_option_id', $value['data']['opcoes'])->where('conforme', 2)->pluck('id')->first() ?? null;
                        $answer = Answer::where('audit_form_list_id', $audit_form_list_id)->where('pergunta_uuid', $value['data']['uuid'])->pluck('resposta')->first() ?? null;

                        if (!empty($answer) && $form_option_list_id == $answer) {
                            $prazo = $today->addDays($value['data']['prazo'])->format('Y-m-d') ?? null;

                            $dataUpdate = [];
                            $dataUpdate['pergunta'] = $value['data']['pergunta'];
                            $dataUpdate['referencia'] = $value['data']['referencia'];
                            $dataUpdate['recomendacao'] = $value['data']['recomendacao'];
                            $dataUpdate['prazo'] = $prazo;
                            $dataUpdate = Nonconformity::updateOrCreate(
                                [
                                    'audit_form_list_id' => $audit_form_list_id,
                                    'pergunta_uuid' => $value['data']['uuid'],
                                ],
                                $dataUpdate,
                            );
                        }
                    }
                }
                Notification::make()
                    ->title('Geração das não conformidades')
                    ->success()
                    ->body('Não conformidades geradas com sucesso!')
                    ->duration(10000)
                    ->send();
            });

            $this->success();
        });
    }

    public function mutateRecordDataUsing(?Closure $callback): static
    {
        $this->mutateRecordDataUsing = $callback;

        return $this;
    }
}
