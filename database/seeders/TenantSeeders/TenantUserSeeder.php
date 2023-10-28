<?php

namespace Database\Seeders\TenantSeeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TenantUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenant = tenant();
        $tenantDomain = $tenant?->domains()?->first()?->domain ?? "{$tenant?->id}.com";

        foreach (range(1, 5) as $int) {
            $tenantUsers[] = [
                'name' => "Usuário {$int}",
                'email' => "usuario_{$int}@{$tenantDomain}",
                'password' => Hash::make('senha123'),
            ];
        }

        foreach (($tenantUsers ?? []) as $userData) {
            User::updateOrCreate(
                [
                    'email' => $userData['email'],
                ],
                $userData
            );
        }
    }
}
