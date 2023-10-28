<?php

namespace Database\Seeders\TenantSeeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statusList = [
            ['titulo' => 'Planejada', 'cor' => '#808080', 'icone' => ''],
            ['titulo' => 'Não iniciada', 'cor' => '#DAA520', 'icone' => ''],
            ['titulo' => 'Em andamento', 'cor' => '#006400', 'icone' => ''],
            ['titulo' => 'Atrasada', 'cor' => '#A52A2A', 'icone' => ''],
            ['titulo' => 'Concluída', 'cor' => '#191970', 'icone' => ''],
            ['titulo' => 'Concluída com Atraso', 'cor' => '#191970', 'icone' => ''],
        ];

        foreach ($statusList as $statusData) {
            Status::updateOrCreate(
                [
                    'titulo' => $statusData['titulo'] ?? null,
                    'cor' => $statusData['cor'] ?? null,
                    'icone' => $statusData['icone'] ?? null,
                ],
                $statusData
            );
        }
    }
}
