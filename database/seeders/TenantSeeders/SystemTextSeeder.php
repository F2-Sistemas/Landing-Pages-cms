<?php

namespace Database\Seeders\TenantSeeders;

use App\Models\Agency;
use App\Models\AgencyCity;
use App\Models\AgencyProvider;
use App\Models\AuditForm;
use App\Models\AuditFormList;
use App\Models\ContentTree;
use App\Models\Tree;
use Illuminate\Database\Seeder;

class SystemTextSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tree = '{"94e1e29b-32e4-4fc6-b119-9ec780f1d9a7":{"name":"Nivel 1","children":{"ae559d04-582f-481f-9387-480db6556573":{"name":"N\u00edvel 1.1","children":[]},"7d8ee7c6-d015-4193-ac84-0af3031d0309":{"name":"N\u00edvel 1.2","children":{"d88bab27-a3aa-447b-9491-952f078846af":{"name":"N\u00edvel 1.1.2","children":[]}}}}},"d110c2f0-50fb-4f1c-b465-2b68013f63a3":{"name":"N\u00edvel 2","children":[]}}';
        $tree = json_decode($tree, true);

        $contentForm = '[{"type":"multiplaEscolha","data":{"tipo_pergunta":"1","uuid":"7de284072f86e758bf75a1ba9f0b5495","obrigatorio":true,"MErespostaUnica":true,"MEimagem":true,"MEjustificativa":true,"pergunta":"Pergunta de ME - Unica","opcoes":"1","referencia":"Esta \u00e9 a refer\u00eancia ...","recomendacao":"Fazer a recomenda\u00e7\u00e3o xxxx","prazo":"60"}},{"type":"multiplaEscolha","data":{"tipo_pergunta":"1","uuid":"cc7bbcc0d2f04911373e42f581158f48","obrigatorio":false,"MErespostaUnica":false,"MEimagem":false,"MEjustificativa":false,"pergunta":"Pergunta de ME - M\u00faltiplas","opcoes":"6","referencia":null,"recomendacao":null,"prazo":null}},{"type":"multiplaEscolhaOpcoes","data":{"tipo_pergunta":"1","uuid":"f51bac97fa268c4077a00b515d02b7f4","obrigatorio":false,"MEOrespostaUnica":true,"pergunta":"Pergunta de ME Digitado - Unica","opcoes":["Resposta 1","Resposta 2","Resposta 3","Resposta 4","Resposta 5"]}},{"type":"multiplaEscolhaOpcoes","data":{"tipo_pergunta":"1","uuid":"061006cb045b60101ea62df7542a959e","obrigatorio":false,"MEOrespostaUnica":false,"pergunta":"Pergunta de ME Digitado - Multipla","opcoes":["Resposta 1","Resposta 2","Resposta 3","Resposta 4","Resposta 5"]}}]';
        $contentForm = json_decode($contentForm, true);

        $agency = Agency::updateOrCreate([
            'id' => 'agencia1',
        ], [
            'name' => 'Agência Teste 1',
            'city_codigo' => 317130,
            // 'logo' => 'logos/default-logo.png',
            'logo' => 'default-logo.png',
        ]);

        AgencyCity::create([
            'city_codigo' => $agency->city_codigo,
        ]);

        AgencyProvider::create(['provider_codigo' => 35021011]);

        $tree = Tree::firstOrCreate(
            [
                'nome' => 'Árvore Teste 1',
                'city_codigo' => 317130,
                'service_id' => 2,
                'provider_codigo' => 35021011,
            ],
            [
                'content_tree' => $tree,
            ]
        );

        ContentTree::create(['nome' => 'Nível 1', 'tree_id' => $tree?->id, 'uuid' => '94e1e29b-32e4-4fc6-b119-9ec780f1d9a7', 'uuid_pai' => '']);
        ContentTree::create(['nome' => 'Nível 1.1', 'tree_id' => $tree?->id, 'uuid' => 'ae559d04-582f-481f-9387-480db6556573', 'uuid_pai' => '94e1e29b-32e4-4fc6-b119-9ec780f1d9a7']);
        ContentTree::create(['nome' => 'Nível 1.2', 'tree_id' => $tree?->id, 'uuid' => '7d8ee7c6-d015-4193-ac84-0af3031d0309', 'uuid_pai' => '94e1e29b-32e4-4fc6-b119-9ec780f1d9a7']);
        ContentTree::create(['nome' => 'Nível 1.2.1', 'tree_id' => $tree?->id, 'uuid' => 'd88bab27-a3aa-447b-9491-952f078846af', 'uuid_pai' => '7d8ee7c6-d015-4193-ac84-0af3031d0309']);
        ContentTree::create(['nome' => 'Nível 2', 'tree_id' => $tree?->id, 'uuid' => 'd110c2f0-50fb-4f1c-b465-2b68013f63a3', 'uuid_pai' => '']);

        $auditForm = AuditForm::firstOrCreate([
            'titulo' => 'Formulário Teste 1',
            'descricao' => 'Exemplo de formulário',
        ], ['conteudo' => $contentForm]);

        $now = now();

        AuditFormList::create([
            'titulo' => fake()->bothify('Fiscalização ***'),
            'content_tree_id' => 1,
            'audit_form_id' => $auditForm?->id,
            'tree_id' => $tree?->id,
            'data_inicio' => $now,
            'data_termino' => $now,
            'respondido' => false,
        ]);
        AuditFormList::create([
            'titulo' => fake()->bothify('Fiscalização ***'),
            'audit_form_id' => $auditForm?->id,
            'tree_id' => $tree?->id,
            'data_inicio' => $now,
            'data_termino' => $now->addDays(1),
            'respondido' => false,
        ]);
        AuditFormList::create([
            'titulo' => fake()->bothify('Fiscalização ***'),
            'audit_form_id' => $auditForm?->id,
            'tree_id' => $tree?->id,
            'data_inicio' => $now->addDays(1),
            'data_termino' => $now->addDays(2),
            'respondido' => false,
        ]);
        AuditFormList::create([
            'titulo' => fake()->bothify('Fiscalização ***'),
            'audit_form_id' => $auditForm?->id,
            'tree_id' => $tree?->id,
            'data_inicio' => $now->addDays(5),
            'data_termino' => $now->addDays(7),
            'respondido' => false,
        ]);
        AuditFormList::create([
            'titulo' => fake()->bothify('Fiscalização ***'),
            'audit_form_id' => $auditForm?->id,
            'tree_id' => $tree?->id,
            'data_inicio' => $now->addDays(1),
            'data_termino' => $now->addDays(1),
            'respondido' => false,
        ]);
    }
}
