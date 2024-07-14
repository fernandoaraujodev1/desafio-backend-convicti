<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'web-access']);
        Permission::create(['name' => 'app-access']);

        $generalDirectorRole = Role::create(['name' => 'general-director']);
        $generalDirectorRole->givePermissionTo(Permission::all());

        $directorRole = Role::create(['name' => 'director']);
        $directorRole->givePermissionTo(['web-access']);

        $managerRole = Role::create(['name' => 'manager']);
        $managerRole->givePermissionTo(['web-access']);

        $salesmanRole = Role::create(['name' => 'salesman']);
        $salesmanRole->givePermissionTo(['app-access']);

        $this->createExampleUsers();
    }

    private function createExampleUsers(): void
    {
        $generalDirector = \App\Models\User::factory()->create([
            'name' => 'General Director',
            'email' => 'general-director@example.com',
            'password' => bcrypt('password'),
        ]);
        $generalDirector->assignRole('general-director');

        $director = \App\Models\User::factory()->create([
            'name' => 'Director',
            'email' => 'director@example.com',
            'password' => bcrypt('password'),
        ]);
        $director->assignRole('director');

        $manager = \App\Models\User::factory()->create([
            'name' => 'Manager',
            'email' => 'manager@example.com',
            'password' => bcrypt('password'),
        ]);
        $manager->assignRole('manager');

        $salesman = \App\Models\User::factory()->create([
            'name' => 'Salesman',
            'email' => 'salesman@example.com',
            'password' => bcrypt('password'),
        ]);
        $salesman->assignRole('salesman');
    }
}
