<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Per-area "last touched" timestamps for the listing-health email triggers.
 *
 * `centers.updated_at` can't serve this purpose: it is declared DEFAULT current_timestamp, so it
 * moves whenever any unrelated column on the row is written, and it says nothing about whether the
 * partner touched their *pricing* or their *calendar*. The automation needs to distinguish those,
 * so each area gets its own column, written from the controller action that changes that area.
 *
 * All four are nullable — an existing center that hasn't been touched since deploy reads as null,
 * and CenterListingHealthService falls back to deriving a date from the underlying tables.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('centers', function (Blueprint $table) {
            $table->timestamp('profile_updated_at')->nullable()->after('is_draft')
                  ->comment('Last time the center profile/settings form was saved');
            $table->timestamp('availability_updated_at')->nullable()->after('profile_updated_at')
                  ->comment('Last time any retreat calendar/date slot was saved');
            $table->timestamp('pricing_updated_at')->nullable()->after('availability_updated_at')
                  ->comment('Last time any retreat or room price was saved');
            $table->timestamp('gallery_updated_at')->nullable()->after('pricing_updated_at')
                  ->comment('Last time a center, retreat or accommodation image was uploaded');
            $table->boolean('email_automation_opt_out')->default(0)->after('gallery_updated_at')
                  ->comment('Partner unsubscribed from non-transactional automation emails');
            $table->timestamp('email_automation_opt_out_at')->nullable()->after('email_automation_opt_out');
        });
    }

    public function down(): void
    {
        Schema::table('centers', function (Blueprint $table) {
            $table->dropColumn([
                'profile_updated_at',
                'availability_updated_at',
                'pricing_updated_at',
                'gallery_updated_at',
                'email_automation_opt_out',
                'email_automation_opt_out_at',
            ]);
        });
    }
};
