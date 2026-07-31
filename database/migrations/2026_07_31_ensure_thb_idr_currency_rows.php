<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $now = now();

        foreach ([
            ['name' => 'THB', 'symbol' => '&#3647; ', 'rate' => 31.500000],
            ['name' => 'IDR', 'symbol' => 'Rp', 'rate' => 16637.520000],
        ] as $currency) {
            $exists = DB::table('currency')->where('name', $currency['name'])->exists();

            if (!$exists) {
                DB::table('currency')->insert([
                    'name'       => $currency['name'],
                    'symbol'     => $currency['symbol'],
                    'rate'       => $currency['rate'],
                    'default'    => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down()
    {
        // Intentionally left blank — do not remove currency rows that may be in use.
    }
};
