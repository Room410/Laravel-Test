<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::create(['name' => 'admin']);
        $user = User::create([
            'name' => 'Dummy',
            'email' => 'dummy@email.com',
            'password' => Hash::make('password'),
        ]);

        $user->roles()->attach($role);
    }
}
