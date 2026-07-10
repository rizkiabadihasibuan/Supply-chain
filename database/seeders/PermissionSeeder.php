<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'manage_users' => 'Ability to CRUD users and roles',
            'manage_countries' => 'Ability to CRUD countries info',
            'manage_ports' => 'Ability to CRUD port datasets',
            'manage_articles' => 'Ability to write and publish analysis articles',
            'view_dashboards' => 'Access to view business intelligence dashboards',
            'resolve_alerts' => 'Ability to log alert resolutions and action plans',
        ];

        $permModels = [];
        foreach ($permissions as $name => $desc) {
            $permModels[$name] = Permission::updateOrCreate(
                ['name' => $name],
                ['description' => $desc]
            );
        }

        // Bind permissions to Admin role (all permissions)
        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole) {
            $adminRole->permissions()->sync(array_values(array_map(fn($m) => $m->id, $permModels)));
        }

        // Bind permissions to Manager role (operational only)
        $managerRole = Role::where('name', 'Manager')->first();
        if ($managerRole) {
            $managerPermissions = [
                $permModels['manage_countries']->id,
                $permModels['manage_ports']->id,
                $permModels['manage_articles']->id,
                $permModels['view_dashboards']->id,
                $permModels['resolve_alerts']->id,
            ];
            $managerRole->permissions()->sync($managerPermissions);
        }

        // Bind permissions to Analyst role (read-only dashboards)
        $analystRole = Role::where('name', 'Analyst')->first();
        if ($analystRole) {
            $analystPermissions = [
                $permModels['view_dashboards']->id,
            ];
            $analystRole->permissions()->sync($analystPermissions);
        }
    }
}
