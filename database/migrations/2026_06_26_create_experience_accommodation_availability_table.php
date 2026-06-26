<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('experience_accommodation_availability', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('experience_id');
            $table->unsignedBigInteger('accommodation_id');
            $table->date('start_date');
            $table->enum('status', ['open', 'few_left', 'full', 'closed'])->default('open');
            $table->unsignedSmallInteger('total_rooms')->default(0);
            $table->unsignedSmallInteger('booked_rooms')->default(0);
            $table->timestamps();

            $table->unique(['experience_id', 'accommodation_id', 'start_date'], 'exp_acc_date_unique');
            $table->index(['experience_id', 'start_date']);
            $table->index('accommodation_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('experience_accommodation_availability');
    }
};