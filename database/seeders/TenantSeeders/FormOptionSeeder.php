<?php

namespace Database\Seeders\TenantSeeders;

use App\Models\FormOption;
use Illuminate\Database\Seeder;

class FormOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['nome' => 'Sim (conforme), Não e Não se Aplica'],
            ['nome' => 'Sim, Não (conforme) e Não se Aplica'],
            ['nome' => 'Atende (conforme) e Não Atende'],
            ['nome' => 'Sim (conforme) e não'],
            ['nome' => 'Sim e não (conforme)'],
            ['nome' => 'Equipamentos'],
        ];

        foreach ($items as $item) {
            FormOption::firstOrCreate([
                'nome' => $item['nome'] ?? null,
            ], $item);
        }
    }
}
