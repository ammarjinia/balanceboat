<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('experience_accomodation_prices', function (Blueprint $table) {
            $table->decimal('single_promo_price', 10, 2)->nullable()->after('double_occupancy_price')
                  ->comment('Promo override for single occupancy price during this date range');
            $table->decimal('double_promo_price', 10, 2)->nullable()->after('single_promo_price')
                  ->comment('Promo override for double occupancy price during this date range');
        });
    }

    public function down()
    {
        Schema::table('experience_accomodation_prices', function (Blueprint $table) {
            $table->dropColumn(['single_promo_price', 'double_promo_price']);
        });
    }
};
