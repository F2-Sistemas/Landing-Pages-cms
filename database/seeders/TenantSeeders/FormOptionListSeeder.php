<?php

namespace Database\Seeders\TenantSeeders;

use App\Models\FormOptionList;
use Illuminate\Database\Seeder;

class FormOptionListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FormOptionList::create(['form_option_id' => 1, 'opcao' => 'Sim', 'conforme' => 1]);
        FormOptionList::create(['form_option_id' => 1, 'opcao' => 'Não', 'conforme' => 2]);
        FormOptionList::create(['form_option_id' => 1, 'opcao' => 'Não se aplica', 'conforme' => 0]);
        FormOptionList::create(['form_option_id' => 2, 'opcao' => 'Sim', 'conforme' => 2]);
        FormOptionList::create(['form_option_id' => 2, 'opcao' => 'Não', 'conforme' => 1]);
        FormOptionList::create(['form_option_id' => 2, 'opcao' => 'Não se aplica', 'conforme' => 0]);
        FormOptionList::create(['form_option_id' => 3, 'opcao' => 'Atende', 'conforme' => 1]);
        FormOptionList::create(['form_option_id' => 3, 'opcao' => 'Não atende', 'conforme' => 2]);
        FormOptionList::create(['form_option_id' => 4, 'opcao' => 'Sim', 'conforme' => 1]);
        FormOptionList::create(['form_option_id' => 4, 'opcao' => 'Não', 'conforme' => 2]);
        FormOptionList::create(['form_option_id' => 5, 'opcao' => 'Sim', 'conforme' => 2]);
        FormOptionList::create(['form_option_id' => 5, 'opcao' => 'Não', 'conforme' => 1]);
        FormOptionList::create(['form_option_id' => 6, 'opcao' => 'Equipamento 1', 'conforme' => 0]);
        FormOptionList::create(['form_option_id' => 6, 'opcao' => 'Equipamento 2', 'conforme' => 0]);
        FormOptionList::create(['form_option_id' => 6, 'opcao' => 'Equipamento 3', 'conforme' => 0]);
        FormOptionList::create(['form_option_id' => 6, 'opcao' => 'Equipamento 4', 'conforme' => 0]);
        FormOptionList::create(['form_option_id' => 6, 'opcao' => 'Equipamento 5', 'conforme' => 0]);
        FormOptionList::create(['form_option_id' => 6, 'opcao' => 'Equipamento 6', 'conforme' => 0]);
        FormOptionList::create(['form_option_id' => 6, 'opcao' => 'Equipamento 7', 'conforme' => 0]);
    }
}
