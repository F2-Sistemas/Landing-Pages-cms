<?php

namespace Database\Seeders;

use App\Models\Domain;
use Illuminate\Database\Seeder;
use App\Models\Tenant;
use Illuminate\Support\Arr;

class FakeTenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(1, 5) as $int) {
            $fakeTenants[] = [
                'name' => "Agência {$int}",
                'id' => $id = "agencia{$int}",
                'domains' => [
                    "{$id}.com",
                    "sub.{$id}.com",
                ],
            ];
        }

        foreach (($fakeTenants ?? []) as $fakeTenantData) {
            $tenant = (function () use ($fakeTenantData) {
                try {
                    return Tenant::firstOrCreate(
                        [
                            'id' => $fakeTenantData['id'],
                        ],
                        Arr::only($fakeTenantData, ['id', 'name']),
                    );
                } catch (\Throwable $th) {
                    //\Log::error($th);

                    return Tenant::find($fakeTenantData['id']);
                }
            })();

            if (!$tenant) {
                throw new \Exception(sprintf('Not created tenant [%s]', $fakeTenantData['id']));
            }

            foreach ($fakeTenantData['domains'] as $domain) {
                if (Domain::where('domain', $domain)->exists()) {
                    continue;
                }

                $tenant->domains()->create([
                    'domain' => $domain,
                ]);
            }
        }
    }
}
