<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Send log for the center-panel email automation.
 *
 * This table is what makes the automation safe to run on a schedule: every evaluator asks it
 * "have I already sent this key to this center recently?" before queueing anything, so a daily
 * cron can re-evaluate the same conditions without spamming partners. It also doubles as the
 * reporting surface for the KPI section of the spec (send volume per template, failure rate).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('center_email_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('center_id');
            $table->unsignedInteger('user_id')->nullable()
                  ->comment('Center Owner the email was addressed to, when resolvable');
            $table->string('email_key', 60)
                  ->comment('Key from config/center_emails.php, e.g. welcome, availability_reminder');
            $table->string('recipient_email', 191)->nullable();
            $table->string('subject', 255)->nullable();
            $table->enum('status', ['queued', 'sent', 'failed', 'skipped'])->default('queued');
            $table->text('error')->nullable();
            $table->json('meta')->nullable()
                  ->comment('Snapshot of the trigger data the email was rendered from');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['center_id', 'email_key', 'created_at'], 'center_email_logs_dedup_index');
            $table->index('email_key');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('center_email_logs');
    }
};
