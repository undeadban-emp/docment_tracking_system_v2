<?php

namespace Database\Seeders;

use App\Models\ManagerUserCount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ManagerUserCountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
          ManagerUserCount::create([
            'name' => 'count',
            'value' => 1,
        ]);
    }
}
