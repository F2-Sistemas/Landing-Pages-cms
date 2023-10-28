<?php

namespace App\Filament\Resources\TreeResource\Pages;

use App\Filament\Resources\TreeResource;
use App\Models\ContentTree;
use App\Models\Tree;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditTree extends EditRecord
{
    protected static string $resource = TreeResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $tree = self::empilhar($data['content_tree']);

        $treeUuid = [];

        foreach ($tree as $value) {
            $treeUuid[] = $value[0];
        }

        $arvoreAntiga = ContentTree::where('tree_id', $record->id)->pluck('uuid', 'id')->toArray();

        foreach ($arvoreAntiga as $key => $value) {
            if (!in_array($value, $treeUuid)) {
                try {
                    ContentTree::where('id', $key)->forceDelete();
                } catch (\Throwable $th) {
                    ContentTree::where('id', $key)->delete();
                }
            }
        }

        // Atualizar a model contentTree
        foreach ($tree as $value) {
            $dataUpdate = [];
            $dataUpdate['nome'] = $value[1];
            $dataUpdate['uuid_pai'] = $value[2];
            $dataUpdate = ContentTree::updateOrCreate(
                [
                    'uuid' => $value[0],
                    'tree_id' => $record->id,
                ],
                $dataUpdate
            );
        }

        // Atualizar a Tree
        $dataUpdate = [];
        $dataUpdate['nome'] = $data['nome'];
        $dataUpdate['provider_codigo'] = $data['provider_codigo'];
        $dataUpdate['city_codigo'] = $data['city_codigo'];
        $dataUpdate['service_id'] = $data['service_id'];
        $dataUpdate['content_tree'] = $data['content_tree'];

        return Tree::updateOrCreate(
            [
                'id' => $record->id,
            ],
            $dataUpdate
        );
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

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
