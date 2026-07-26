<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('experience_views', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('experience_id');
            $table->string('visitor_hash', 64);
            $table->date('viewed_date');
            $table->string('country_code', 2)->nullable();
            $table->string('country_name', 100)->nullable();
            $table->timestamps();

            $table->unique(['experience_id', 'visitor_hash', 'viewed_date'], 'exp_view_unique_visitor_day');
            $table->index('experience_id');
            $table->index('country_code');
        });
    }

    public function down()
    {
        Schema::dropIfExists('experience_views');
    }
};
