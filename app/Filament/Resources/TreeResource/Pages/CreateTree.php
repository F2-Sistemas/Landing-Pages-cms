<?php

namespace App\Filament\Resources\TreeResource\Pages;

use App\Filament\Resources\TreeResource;
use App\Models\ContentTree;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTree extends CreateRecord
{
    protected static string $resource = TreeResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $dataSave = static::getModel()::create($data);

        $tree = self::empilhar($data['content_tree']);

        foreach ($tree as $value) {
            $dataInsert = new ContentTree();
            $dataInsert->nome = $value[1];
            $dataInsert->uuid = $value[0];
            $dataInsert->uuid_pai = $value[2];
            $dataInsert->tree_id = $dataSave->id;
            $dataInsert->save();
        }

        return $dataSave;
    }

    protected static array $tree = [];

    protected static function empilhar($dados, $paiKey = null)
    {
        foreach ($dados as $key => $value) {
            if (!empty($value['children'])) {
                self::empilhar($value['children'], $key);
            }
            self::$tree[] = [$key, $value['name'], $paiKey];
        }

        return self::$tree;
    }
}
