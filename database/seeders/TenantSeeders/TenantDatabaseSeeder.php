<?php

namespace Database\Seeders\TenantSeeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\Helpers\SeedAndLog;
use Illuminate\Database\Seeder;

class TenantDatabaseSeeder extends Seeder
{
    use SeedAndLog;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!tenancy()->initialized) {
            throw new \Exception('Only for tenant context');
        }

        $this->callGroup(static::getSeedersGroups());
    }

    public static function getSeedersGroups(): array
    {
        return [
            /**
             * Itens que só serão executados 1 vez (normalmente na instalação do ambiente)
             * Cada item pode ser chamado individualmente se ncessário
             */
            'once' => [
                //
            ],

            /**
             * Itens que serão executados sempre que esse seeder for executado
             */
            'ever' => [
                FormOptionSeeder::class,
                FormOptionListSeeder::class,
                StatusSeeder::class,
                ServiceSeeder::class,
                ActionTypeSeeder::class,
                SystemTextSeeder::class,
                TenantUserSeeder::class,
            ],
        ];
    }
}
