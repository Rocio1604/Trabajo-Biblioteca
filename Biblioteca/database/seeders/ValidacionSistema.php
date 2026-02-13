<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ValidacionSistema extends Seeder
{
    public function run(): void
    {
        DB::table('validaciones_sistema')->insert([
            [
                'referencia_id' => 1,
                'firma_digital' => '$2y$12$ZP5BFc2g4LPMV7lV0a66reeGUuM2kNpjqUqTCmak8XblUIff1YTZa',
            ],
            [
                'referencia_id' => 2,
                'firma_digital' => '$2y$12$ZP5BFc2g4LPMV7lV0a66reeGUuM2kNpjqUqTCmak8XblUIff1YTZa',
            ],
        ]);
    }
}