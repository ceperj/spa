<?php

namespace Database\Seeders;

use App\Constants;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BatteriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('batteries')->insert($this->getBattery(1, false));
        DB::table('batteries')->insert($this->getBattery(3, true));
        DB::table('batteries')->insert($this->getBattery(9, false));
        DB::table('batteries')->insert($this->getBattery(11, true));
        DB::table('batteries')->insert($this->getBattery(18, true));
        DB::table('batteries')->insert($this->getBattery(28, true));
        DB::table('batteries')->insert($this->getBattery(29, false));
    }

    function getBattery($date, $active){
        return [
            'date' => $date,
            'status' => $active ? Constants::STATUS_ACTIVE : Constants::STATUS_INACTIVE,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
    }
}
