<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
   /**
    * Seed the application's database.
    */
   public function run(): void
   {
      // User::factory(10)->create();

      // User::factory()->create([
      //    'name' => 'Test User',
      //    'email' => 'test@example.com',
      // ]);

      DB::table('users')->insert([
         'name' => 'Admin',
         'email' => 'adminacademiatopfitness@dev.com',
         'password' => Hash::make('adminacademiatopfitness@123'),
      ]);
      DB::table('roles')->insert([
         'name' => 'admin',
      ]);
      DB::table('roles')->insert([
         'name' => 'aluno',
      ]);
      DB::table('role_user')->insert([
         'user_id' => 1,
         'role_id' => 1,
      ]);
      DB::table('abilities')->insert([
         'name' => 'admin',
      ]);
      DB::table('ability_role')->insert([
         'role_id' => 1,
         'ability_id' => 1,
      ]);
   }
}
