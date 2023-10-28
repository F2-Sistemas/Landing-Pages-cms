<?php

namespace Database\Seeders\TenantSeeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serviceList = [
            ['nome' => 'Serviço de Abastecimento de Água'],
            ['nome' => 'Serviço de Coleta de Esgoto'],
            ['nome' => 'Serviço de Coleta de Resíduos Sólidos'],
        ];

        foreach ($serviceList as $serviceData) {
            Service::updateOrCreate(
                [
                    'nome' => $serviceData['nome'] ?? null,
                ],
                $serviceData
            );
        }
    }
}
