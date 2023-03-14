<?php

namespace Database\Seeders;

use App\Models\Office;
use App\Models\Service;
use App\Models\Requirement;
use App\Models\ServiceProcess;
use Illuminate\Database\Seeder;
use App\Models\SubServiceProcess;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServiceProcessingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {



        // Requirement::create([
        //     'where_to_secure' => 'DISTRICT ADOFs',
        //     'description' => 'Duly signed DTR by the School Head',
        //     'service_id' => 1,
        // ]);

        // Requirement::create([
        //     'where_to_secure' => 'District ADOFs',
        //     'description' => 'Duly signed DTR by the ASDS',
        //     'service_id' => 1,
        // ]);

        // Requirement::create([
        //     'where_to_secure' => 'SDO Personnel',
        //     'description' => 'Duly sgined DTR by the SDS',
        //     'service_id' => 1,
        // ]);

        // ServiceProcess::create([
        //     'code' => 'SOD-I',
        //     'location' => '',
        //     'responsible' => 'School Heads',
        //     'fees_to_paid' => 0,
        //     'description' => 'School Heads',
        //     'index' => 1,
        //     'responsible_user' => 2,
        //     'manager_id' => 5,
        // ]);

        // ServiceProcess::create([
        //     'code' => 'SOD-I',
        //     'location' => '',
        //     'responsible' => 'AO/Cluster In-Charge (ADOF)',
        //     'fees_to_paid' => 0,
        //     'description' => 'AO/Cluster In-Charge (ADOF)',
        //     'index' => 2,
        //     'responsible_user' => 3,
        //     'manager_id' => 5,
        // ]);

        // ServiceProcess::create([
        //     'code' => 'SOD-I',
        //     'location' => Office::get()[1]->code,
        //     'responsible' => 'PAC-D',
        //     'fees_to_paid' => 0,
        //     'description' => 'Received by PAC-D',
        //     'index' => 3,
        //     'responsible_user' => 4,
        //     'manager_id' => 5,
        // ]);


        // ServiceProcess::create([
        //     'code' => 'SOD-I',
        //     'location' => Office::get()[1]->code,
        //     'responsible' => 'Records In-Charge',
        //     'fees_to_paid' => 0,
        //     'action' => 'Received DTR Submitted',
        //     'index' => 4,
        //     'responsible_user' => 5,
        //     'manager_id' => 5,
        // ]);

        // ServiceProcess::create([
        //     'code' => 'SOD-I',
        //     'location' => Office::get()[1]->code,
        //     'responsible' => 'ADA-VI Personnel Section',
        //     'fees_to_paid' => 0,
        //     'action' => 'Checked the Completeness of the submitted DTR',
        //     'index' => 5,
        //     'responsible_user' => 6,
        //     'manager_id' => 5,
        // ]);
    }
}
