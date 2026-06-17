<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOccupancyPricesToExperienceAcommodationTables extends Migration
{
    public function up()
    {
        // Base rates on the accommodation-experience link
        Schema::table('experience_accomodations', function (Blueprint $table) {
            $table->decimal('single_occupancy_price', 10, 2)->nullable()->after('price_per_night_per_guest')
                  ->comment('Solo rate — when 1 guest occupies the room alone');
            $table->decimal('double_occupancy_price', 10, 2)->nullable()->after('single_occupancy_price')
                  ->comment('Shared rate per person — when 2 guests share the room');
        });

        // Seasonal override rates per date range
        Schema::table('experience_accomodation_prices', function (Blueprint $table) {
            $table->decimal('single_occupancy_price', 10, 2)->nullable()->after('price_per_night_per_guest')
                  ->comment('Solo rate override for this date range');
            $table->decimal('double_occupancy_price', 10, 2)->nullable()->after('single_occupancy_price')
                  ->comment('Shared rate per person override for this date range');
        });
    }

    public function down()
    {
        Schema::table('experience_accomodations', function (Blueprint $table) {
            $table->dropColumn(['single_occupancy_price', 'double_occupancy_price']);
        });

        Schema::table('experience_accomodation_prices', function (Blueprint $table) {
            $table->dropColumn(['single_occupancy_price', 'double_occupancy_price']);
        });
    }
}
