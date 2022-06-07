<?php

namespace Database\Seeders;

use App\Constants;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('jobs')->insert($this->getJob('Programador(a)', true));
        DB::table('jobs')->insert($this->getJob('Administrador(a)', true));
        DB::table('jobs')->insert($this->getJob('Diretor Geral(a)', true));
        DB::table('jobs')->insert($this->getJob('Gerente Executivo(a)', true));
        DB::table('jobs')->insert($this->getJob('Secretário(a)', true));
        DB::table('jobs')->insert($this->getJob('Cozinheiro(a)', true));
        DB::table('jobs')->insert($this->getJob('Copeiro(a)', true));
        DB::table('jobs')->insert($this->getJob('Mordomo(a)', false));
        DB::table('jobs')->insert($this->getJob('Faxineiro(a)', true));
        DB::table('jobs')->insert($this->getJob('Babá', false));
        DB::table('jobs')->insert($this->getJob('Carcereiro(a)', false));
        DB::table('jobs')->insert($this->getJob('Guarda', true));
        DB::table('jobs')->insert($this->getJob('Promotor(a)', true));
    }

    function getJob($name, $active)
    {
        return [
            'name' => $name,
            'user_id' => 1,
            'status' => $active ? Constants::STATUS_ACTIVE : Constants::STATUS_INACTIVE,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
    }
}
