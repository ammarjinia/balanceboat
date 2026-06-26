<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inquiries', function (Blueprint $table) {
            // Add new fields for quick lead form
            $table->string('retreat_type')->nullable()->after('message')->comment('Type of retreat: yoga_retreat, detox, rejuvenation_meditation, weight_loss, panchakarma, other_ayurvedic');
            $table->string('destination')->nullable()->after('retreat_type')->comment('Preferred destination: india, thailand, indonesia, open');
            $table->string('budget')->nullable()->after('destination')->comment('Budget range: under_3k, 3k_5k, 5k_8k, 8k_12k, 12k_18k, 18k_25k, 25k_40k, 40k_60k, 60k_100k, above_100k, flexible');
            $table->string('timeline')->nullable()->after('budget')->comment('Travel timeline: within_2w, next_month, 1_3m, 3_6m, exploring');
            $table->boolean('whatsapp')->default(0)->after('timeline')->comment('Is the phone number on WhatsApp: 0 or 1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropColumn(['retreat_type', 'destination', 'budget', 'timeline', 'whatsapp']);
        });
    }
};