<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'description' => 'System Administrator with full access',
                'permissions' => [
                    'manage_users',
                    'manage_countries',
                    'manage_ports',
                    'manage_articles',
                    'view_dashboards',
                    'resolve_alerts',
                ],
            ],
            [
                'name' => 'Manager',
                'description' => 'Supply Chain Manager with operational access',
                'permissions' => [
                    'manage_countries',
                    'manage_ports',
                    'manage_articles',
                    'view_dashboards',
                    'resolve_alerts',
                ],
            ],
            [
                'name' => 'Analyst',
                'description' => 'Data Analyst with read-only access to statistics',
                'permissions' => [
                    'view_dashboards',
                ],
            ]
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['name' => $roleData['name']],
                [
                    'description' => $roleData['description'],
                    'permissions' => $roleData['permissions']
                ]
            );
        }
    }
}
