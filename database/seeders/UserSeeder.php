<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'Admin')->first();
        $managerRole = Role::where('name', 'Manager')->first();
        $analystRole = Role::where('name', 'Analyst')->first();

        // Create Admin
        User::updateOrCreate(
            ['email' => 'admin@supplyrisk.io'],
            [
                'role_id' => $adminRole->id,
                'name' => 'System Admin',
                'password' => Hash::make('password'),
            ]
        );

        // Create Manager
        User::updateOrCreate(
            ['email' => 'manager@supplyrisk.io'],
            [
                'role_id' => $managerRole->id,
                'name' => 'John Doe (Manager)',
                'password' => Hash::make('password'),
            ]
        );

        // Create Analyst
        User::updateOrCreate(
            ['email' => 'analyst@supplyrisk.io'],
            [
                'role_id' => $analystRole->id,
                'name' => 'Jane Smith (Analyst)',
                'password' => Hash::make('password'),
            ]
        );
    }
}
