<?php

namespace Database\Seeders\TenantSeeders;

use App\Models\ActionType;
use Illuminate\Database\Seeder;

class ActionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ActionType::insert([
            ['action_type' => 'Não conformidade'],
            ['action_type' => 'Recomendação'],
        ]);
    }
}
