<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Office;
use App\Models\Service;
use App\Models\ServiceProcess;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            OfficeSeeder::class,
            PositionSeeder::class,
            UserSeeder::class,
            ManagerUserCountSeeder::class,
            // ServiceProcessingSeeder::class,
        ]);
        //  User::factory(5)->create();
        // Service::factory(1)->create();
    }
}
