<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
   /**
    * Seed the application's database.
    */
   public function run(): void
   {
      $guardName = config('auth.defaults.guard', 'web');

      foreach (config('access-control.permissions', []) as $permissionName => $label) {
         Permission::findOrCreate($permissionName, $guardName);
      }

      foreach (config('access-control.default_roles', []) as $roleName => $permissions) {
         $role = Role::findOrCreate($roleName, $guardName);
         $role->syncPermissions($permissions);
      }

      $admin = User::firstOrCreate(
         ['email' => 'adminacademiatopfitness@dev.com'],
         [
            'name' => 'Admin',
            'password' => Hash::make('adminacademiatopfitness@123'),
            'status' => true,
         ]
      );

      $admin->assignRole('admin');
   }
}
