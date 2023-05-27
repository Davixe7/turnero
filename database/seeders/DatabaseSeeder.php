<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt(123456)
        ]);

        $services = ['recepcion', 'limpieza', 'alineación', 'calibración', 'montaje'];

        $index = 0;
        foreach($services as $serviceName){
            \App\Models\Service::create([
                'index' => $index,
                'name' => $serviceName,
                'user_id' => 1
            ]);
            $index++;
        }

        //$index = \App\Models\Order::whereBetween('created_at', [\Carbon\Carbon::now()->startOfDay(), \Carbon\Carbon::now()->endOfDay()])->latest()->first(['index']);
    }
}
