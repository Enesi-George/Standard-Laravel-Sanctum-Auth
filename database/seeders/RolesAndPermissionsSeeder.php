<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Team;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        //Create Team
        //Create Team
        $teams = [
            Team::create(['name' => 'Alpha', 'description' => 'we are the alpha team with ultimate authority']),
            Team::create(['name' => 'Beta', 'description' => 'we are the beta team with less authority']),
            Team::create(['name' => 'Gamma', 'description' => 'we are the gamma team with lesser authority']),
        ];

        // Create permissions
        Permission::create(['name' => 'create super admin', 'guard_name' => 'api']);
        Permission::create(['name' => 'edit super admin', 'guard_name' => 'api']);
        Permission::create(['name' => 'create admin', 'guard_name' => 'api']);
        Permission::create(['name' => 'edit admin', 'guard_name' => 'api']);
        Permission::create(['name' => 'create super user', 'guard_name' => 'api']);
        Permission::create(['name' => 'view all users', 'guard_name' => 'api']);
        Permission::create(['name' => 'delete all users', 'guard_name' => 'api']);
        Permission::create(['name' => 'edit users detail', 'guard_name' => 'api']);


        // Create roles and assign created permissions
        $super_admin_role = Role::create(['name' => 'super-admin', 'guard_name' => 'api']);
        $admin_role = Role::create(['name' => 'admin', 'guard_name' => 'api']);

        $super_admin_role->givePermissionTo(Permission::all());
        $admin_role->givePermissionTo([
            'view all users',
            'delete all users',
            'edit users detail',
            'create super admin',
        ]);

        // Assign team IDs randomly to roles
        foreach ([$super_admin_role, $admin_role] as $role) {
            $randomTeam = $teams[array_rand($teams)];
            $role->team_id = $randomTeam->id;
            $role->save();
        }
    }
}
