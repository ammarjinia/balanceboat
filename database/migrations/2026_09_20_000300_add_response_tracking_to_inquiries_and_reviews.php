<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Response tracking for the "unanswered inquiry" and "unanswered review" email triggers.
 *
 * CenterLeadsController@respond currently records a response only by appending a line to
 * `inquiries.note` and nudging `stage` off 'new' — neither is a reliable, queryable signal for an
 * SLA timer, so the inquiry gets an explicit `responded_at`.
 *
 * `reviews` has no concept of a center reply at all, which leaves email 24 ("Respond to Your
 * Reviews") with no exit condition. These two columns give it one; the center-panel UI for writing
 * the reply is a separate piece of work, but the automation can already detect the unanswered state.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->timestamp('responded_at')->nullable()->after('note')
                  ->comment('First time the center replied to this lead, for response-SLA emails');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->text('center_response')->nullable()->after('verified');
            $table->timestamp('center_response_at')->nullable()->after('center_response');
        });
    }

    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropColumn('responded_at');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['center_response', 'center_response_at']);
        });
    }
};
