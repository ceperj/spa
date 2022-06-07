<?php

namespace Database\Seeders;

use App\Constants;
use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BanksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('banks')->insert($this->getBankData(001, 'Banco do Brasil S.A.', true));
        DB::table('banks')->insert($this->getBankData(237, 'Bradesco S.A.', true));
        DB::table('banks')->insert($this->getBankData(290, 'Pagseguro Internet S.A.', false));
        DB::table('banks')->insert($this->getBankData(380, 'PicPay Serviços S.A.', false));
        DB::table('banks')->insert($this->getBankData(041, 'Banrisul (Banco do Estado do Rio Grande do Sul)', false));
        DB::table('banks')->insert($this->getBankData(260, 'Nu Pagamentos S.A.', true));
    }

    private function getBankData($code, $name, $active){
        return [
            'code' => $code,
            'name' => $name,
            'status' => $active ? Constants::STATUS_ACTIVE : Constants::STATUS_INACTIVE,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
    }
}
