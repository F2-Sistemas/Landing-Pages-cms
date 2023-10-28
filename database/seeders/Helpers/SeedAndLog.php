<?php

namespace Database\Seeders\Helpers;

trait SeedAndLog
{
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
                //
            ],
        ];
    }

    public function callGroup(?array $seedersGroups = null)
    {
        $seedersGroups ??= static::getSeedersGroups();

        collect($seedersGroups['once'] ?? null)->each(function ($seederClass) {
            $forceMode = $this->command->option('force');

            if (!$forceMode && is_file(static::getSeederLogFilePath($seederClass))) {
                return;
            }

            $this->callAndLog($seederClass);
        });

        collect($seedersGroups['ever'] ?? null)->each(fn ($seederClass) => $this->callAndLog($seederClass));
    }

    public function callAndLog(string $seederClass)
    {
        if (!class_exists($seederClass)) {
            return;
        }

        $this->call($seederClass);

        file_put_contents(
            static::getSeederLogFilePath($seederClass),
            now()->format('c') . PHP_EOL,
            FILE_APPEND
        );
    }

    public static function getSeederLogFilePath(string $seederClass): string
    {
        $tenantId = tenant('id');

        return database_path(
            str($seederClass)
                ->afterLast('\\')
                ->prepend($tenantId ? "{$tenantId}_" : '')
                ->prepend('seeders/seed-control/')
                ->append('.log')
        );
    }
}
