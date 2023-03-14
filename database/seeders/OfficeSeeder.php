<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Office;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $offices = array(
            array('code' => 1001, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => 'Bislig District Hospital'),
            array('code' => 1002, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => 'Cortes Municipal Hospital'),
            array('code' => 1003, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => 'Hinatuan District Hospital'),
            array('code' => 1004, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => 'Internal Audit Service Office'),
            array('code' => 1005, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => 'Lianga District Hospital'),
            array('code' => 1006, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => 'Lingig Medicare Community Hospital'),
            array('code' => 1007, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => 'Madrid District Hospital'),
            array('code' => 1008, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => 'Marihatag District Hospital'),
            array('code' => 1009, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => "Provincial Accountant's Office"),
            array('code' => 1010, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => "Provincial Administrator's Office"),
            array('code' => 1011, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => "Provincial Agriculturist's Office"),
            array('code' => 1012, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => "Provincial Assessor's Office"),
            array('code' => 1013, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => 'Provincial Budget Office'),
            array('code' => 1014, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => 'Provincial Disaster Risk Reduction & Management Office'),
            array('code' => 1015, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => "Provincial Engineer's Office-Administrative Division"),
            array('code' => 1016, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => "Provincial Engineer's Office-Construction & Maintenance Division"),
            array('code' => 1017, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => "Provincial Engineer's Office-Motorpool Division"),
            array('code' => 1018, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => 'Provincial Environment and Natural Resources Office-LGU'),
            array('code' => 1019, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => 'Provincial Fisheries & Aquatic Resources Office'),
            array('code' => 1020, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => 'Provincial General Services Office'),
            array('code' => 1021, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => "Provincial Governor's Office"),
            array('code' => 1022, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => "Provincial Governor's Office-Nutrition"),
            array('code' => 1023, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => "Provincial Governor's Office-PEDIPU"),
            array('code' => 1024, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => "Provincial Governor's Office-POPCOM"),
            array('code' => 1025, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => "Provincial Governor's Office-Warden"),
            array('code' => 1026, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => 'Provincial Health Office'),
            array('code' => 1027, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => 'Provincial Human Resource Management Office'),
            array('code' => 1028, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => 'Provincial Legal Office'),
            array('code' => 1029, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => 'Provincial Planning & Development Office'),
            array('code' => 1030, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => "Provincial Prosecutor's Office"),
            array('code' => 1031, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => 'Provincial Social Welfare and Development Office'),
            array('code' => 1032, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => 'Provincial Tourism Office'),
            array('code' => 1033, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => "Provincial Treasurer's Office"),
            array('code' => 1034, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => "Provincial Veterinary Office"),
            array('code' => 1035, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => 'San Miguel Community Hospital'),
            array('code' => 1036, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => 'Tanggapan ng Sangguniang Panlalawigan'),
            array('code' => 1037, 'created_at' => Carbon::now() ,'updated_at' => Carbon::now(), 'description' => "Provincial Vice Governor's Office"),
         );

        DB::table('offices')->insert($offices);
        // Office::create([
        //     'code' => 1001,
        //     'description' => 'Faculty'
        // ]);

        // Office::create([
        //     'code' => 1002,
        //     'description' => 'Human Resource and Management Office'
        // ]);
    }
}
