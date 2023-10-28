<?php

namespace App\Filament\Resources\FormOptionResource\Pages;

use App\Filament\Resources\FormOptionResource;
use App\Models\FormOptionList;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateFormOption extends CreateRecord
{
    protected static string $resource = FormOptionResource::class;

    // qual a funcao para rodar apos a criacao do registro
    // protected function handleRecordCreation(array $data): Model
    // {
    //     $i = 1;
    //     foreach ($data['opcoes'] as $opcao) {
    //         $conforme = null;
    //         if ($opcao['conforme']=='1') {
    //             $conforme = true;
    //         } elseif ($opcao['conforme']=='2') {
    //             $conforme = false;
    //         }
    //         $dados[]=[
    //             'form_option_id' => 'CÓDIGO QUE AINDA SERÁ CRIADO',
    //             'opcao' => $opcao['opcao'],
    //             'conforme' => $conforme,
    //             'ordem' => $i,
    //         ];
    //         $i++;
    //     }

    //     $saveData = FormOptionList::upsert(
    //         $dados,
    //         ['form_option_id','opcao','conforme','ordem']
    //     );

    //     $saveData = static::getModel()::create($data);
    //     return $saveData;
    // }
}
