<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->string('title');
            $table->string('type'); // text, number, radio, select, checkbox
            $table->text('options')->nullable();
            $table->boolean('is_required')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('event_participant_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_booking_participant_id');
            // Laravel's generated name exceeds MySQL's 64-character limit.
            $table->foreign('event_booking_participant_id', 'event_answers_participant_fk')
                ->references('id')->on('event_booking_participants')->cascadeOnDelete();
            $table->foreignId('event_question_id')->constrained('event_questions')->cascadeOnDelete();
            $table->text('answer')->nullable();
            $table->timestamps();

            $table->index(['event_booking_participant_id', 'event_question_id'], 'participant_question_idx');
        });

        if (!Schema::hasColumn('event_booking_participants', 'custom_answers')) {
            Schema::table('event_booking_participants', function (Blueprint $table) {
                $table->json('custom_answers')->nullable()->after('certificate_sent_at');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('event_participant_answers');
        Schema::dropIfExists('event_questions');
        if (Schema::hasColumn('event_booking_participants', 'custom_answers')) {
            Schema::table('event_booking_participants', function (Blueprint $table) {
                $table->dropColumn('custom_answers');
            });
        }
    }
};
