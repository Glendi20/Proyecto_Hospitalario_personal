<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'Admin',
            'Médico',
            'Enfermera',
            'TecnicoLab',
            'Recepcionista',
            'Bioquimico',
        ];

        foreach ($roles as $role) {
            Role::query()->firstOrCreate(
                ['name' => $role, 'guard_name' => 'api']
            );
        }

        $permiso = Permission::query()->firstOrCreate(
            ['name' => 'lab.results.validate', 'guard_name' => 'api']
        );

        Role::query()
            ->where('name', 'Bioquimico')
            ->first()
            ?->givePermissionTo($permiso);
    }
}
