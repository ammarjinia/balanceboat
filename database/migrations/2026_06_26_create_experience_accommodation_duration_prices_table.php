<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('experience_accommodation_duration_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('experience_id');
            $table->unsignedBigInteger('accommodation_id');
            $table->unsignedSmallInteger('duration_days')->comment('Duration in days, e.g. 7, 14, 21');
            $table->decimal('single_price', 10, 2)->nullable()->comment('Total room price for single occupancy for the full duration');
            $table->decimal('double_price', 10, 2)->nullable()->comment('Total room price for double occupancy for the full duration');
            $table->string('currency', 5)->default('INR');
            $table->timestamps();

            $table->unique(['experience_id', 'accommodation_id', 'duration_days'], 'exp_acc_dur_unique');
            $table->index(['experience_id', 'accommodation_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('experience_accommodation_duration_prices');
    }
};
