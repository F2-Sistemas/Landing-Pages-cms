<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\Helpers\SeedAndLog;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use SeedAndLog;

    public static function getSeedersGroups(): array
    {
        return [
            /**
             * Itens que só serão executados 1 vez (normalmente na instalação do ambiente)
             * Cada item pode ser chamado individualmente se ncessário
             */
            'once' => [
                CitySeeder::class,
                ProviderSeeder::class,
                FakeTenantSeeder::class,
            ],

            /**
             * Itens que serão executados sempre que esse seeder for executado
             */
            'ever' => [
                SystemUserSeeder::class,
            ],
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->callGroup();
    }
}
