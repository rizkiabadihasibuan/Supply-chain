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
            ],
            [
                'name' => 'Manager',
                'description' => 'Supply Chain Manager with operational access',
            ],
            [
                'name' => 'Analyst',
                'description' => 'Data Analyst with read-only access to statistics',
            ]
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['name' => $roleData['name']],
                ['description' => $roleData['description']]
            );
        }
    }
}
