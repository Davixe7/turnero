<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = ['root', 'admin', 'employee'];

        foreach($roles as $role){
            Role::create(['name' => $role]);
        }

        User::factory()->create([
            'name' => 'David',
            'email' => 'root@turnero.com',
            'password' => bcrypt(123456)
        ]);
        User::first()->assignRole('root');
    }
}
