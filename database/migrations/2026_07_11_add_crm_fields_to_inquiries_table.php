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
            $table->string('stage', 30)->default('new')->after('source')
                  ->comment('CRM pipeline stage: new, proposal_sent, negotiation, won, lost');
            $table->decimal('deal_value', 12, 2)->nullable()->after('stage')
                  ->comment('Estimated/quoted deal value for the CRM pipeline');
            $table->text('loss_reason')->nullable()->after('deal_value')
                  ->comment('Reason documented when a lead stage is set to lost');
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
            $table->dropColumn(['stage', 'deal_value', 'loss_reason']);
        });
    }
};
