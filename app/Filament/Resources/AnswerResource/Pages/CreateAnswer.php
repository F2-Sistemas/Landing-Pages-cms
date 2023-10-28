<?php

namespace App\Filament\Resources\AnswerResource\Pages;

use App\Filament\Resources\AnswerResource;
use App\Models\Answer;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateAnswer extends CreateRecord
{
    protected static string $resource = AnswerResource::class;

    protected function handleRecordCreation(array $data): Model
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
                } else {
                    $dadosInclusao[$key] = $value;
                }
            }
        }

        foreach ($dadosInclusao as $key => $value) {
            $dataInsert = new Answer();
            $dataInsert->user_id = auth()->user()->id;
            $dataInsert->audit_form_list_id = $auditFormList;
            $dataInsert->pergunta_uuid = $key;
            $dataInsert->resposta_type = $types[$key];

            if (is_array($dadosInclusao[$key])) {
                $dataInsert->resposta = json_encode($dadosInclusao[$key]);
            } else {
                $dataInsert->resposta = $dadosInclusao[$key];
            }
            $dataInsert->save();
        }

        return $dataInsert;
    }
}
