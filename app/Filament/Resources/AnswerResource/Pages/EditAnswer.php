<?php

namespace App\Filament\Resources\AnswerResource\Pages;

use App\Filament\Resources\AnswerResource;
use App\Models\Answer;
use App\Models\AuditFormList;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditAnswer extends EditRecord
{
    protected static string $resource = AnswerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $auditFormList = $data['audit_form_list_id'];
        unset($data['audit_form_list_id']);
        $types = $data['types'];
        unset($data['types']);

        $dadosInclusao = [];

        foreach ($data as $key => $value) {
            if (strpos($key, '_label') > 0) {
                // Label
                $dadosInclusao[str_replace('_label', '', $key)][0]['label'] = $value;
            } elseif (strpos($key, '_comentario') > 0) {
                // Comentários
                $dadosInclusao[str_replace('_comentario', '', $key)]['comentario'] = $value;
            } elseif (strpos($key, '_images') > 0) {
                // Imagens das perguntas de Multipla Escolha
                $dadosInclusao[str_replace('_images', '', $key)]['images'] = $value;
            } else {
                if ($types[$key] == 'arquivo' || $types[$key] == 'foto') {
                    if (is_array($value)) {
                        if (!empty($value)) {
                            if (array_key_exists(0, $value) && is_array($value[0])) {
                                // multiplos arquivos multiplos labels
                                foreach ($value as $keyArquivo => $valueArquivo) {
                                    $dadosInclusao[$key][$keyArquivo]['arquivo'] = $valueArquivo['arquivo'];
                                    $dadosInclusao[$key][$keyArquivo]['label'] = $valueArquivo['label'];
                                }
                            } else {
                                // multiplos arquivos um label
                                foreach ($value as $keyArquivo => $valueArquivo) {
                                    $dadosInclusao[$key][$keyArquivo]['arquivo'] = $valueArquivo;
                                }
                            }
                        }
                    } else {
                        $dadosInclusao[$key][0]['arquivo'] = $value;
                    }
                } elseif ($types[$key] == 'multiplaEscolha' || $types[$key] == 'multiplaEscolhaOpcoes') {
                    if (is_array($value)) {
                        $dadosInclusao[$key]['answer'] = json_encode($value);
                    } else {
                        $dadosInclusao[$key]['answer'] = $value;
                    }
                } else {
                    $dadosInclusao[$key]['answer'] = $value;
                }
            }
        }

        foreach ($dadosInclusao as $key => $value) {
            $dataUpdate = [];
            $dataUpdate['user_id'] = auth()->user()->id;
            $dataUpdate['resposta_type'] = $types[$key];

            if (!isset($dadosInclusao[$key]['answer'])) {
                $dataUpdate['resposta'] = json_encode($dadosInclusao[$key]);
            } else {
                $dataUpdate['resposta'] = $dadosInclusao[$key]['answer'];
                $dataUpdate['comentario'] = $dadosInclusao[$key]['comentario'] ?? null;
                $dataUpdate['images'] = $dadosInclusao[$key]['images'] ?? [];
            }
            $dataUpdate = Answer::updateOrCreate(
                [
                    'audit_form_list_id' => $auditFormList,
                    'pergunta_uuid' => $key,
                ],
                $dataUpdate
            );
        }

        $updateRespondido = AuditFormList::where('id', $auditFormList)->update(['respondido' => true]);

        return $dataUpdate;
    }
}
