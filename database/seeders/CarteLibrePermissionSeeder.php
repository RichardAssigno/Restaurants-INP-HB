<?php

namespace Database\Seeders;

use App\Policies\CarteLibrePolicy;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class CarteLibrePermissionSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            CarteLibrePolicy::VIEW_ANY,
            CarteLibrePolicy::CREATE,
            CarteLibrePolicy::UPDATE,
            CarteLibrePolicy::ACTIVATE,
            CarteLibrePolicy::DELETE,
        ] as $permission) {
            Permission::findOrCreate($permission, 'operateur');
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
