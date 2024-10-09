<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->index()->constrained()->onDelete('cascade');
            $table->integer('type')->unsigned()->default(0); // 0: 개인, 1: 단체
            $table->string('xlsx');
            $table->timestamp('cancel_time')->nullable();
            $table->timestamps();
        });

        Schema::create('user_event_surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_event_id')->index()->constrained()->onDelete('cascade');
            $table->foreignId('survey_id')->index()->constrained(table: 'event_surveys')->onDelete('cascade');
            $table->string('answer')->nullable(); // JSON 형태로 저장
        });

        Schema::create('user_event_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_event_id')->index()->constrained()->onDelete('cascade');
            $table->foreignId('event_information_id')->index()->constrained(table: 'event_information')->onDelete('cascade');
            $table->string('answer')->nullable();
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_event_id')->index()->constrained()->onDelete('cascade');
            $table->float('rate');
            $table->text('content');
            $table->integer('like')->unsigned()->default(0);
            $table->timestamps();
        });

        Schema::create('booth_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->index()->constrained()->onDelete('cascade');
            $table->foreignId('booth_id')->index()->constrained(table: 'event_booths')->onDelete('cascade');
        });

        Schema::create('event_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->index()->constrained()->onDelete('cascade');
        });

        Schema::create('review_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->constrained()->onDelete('cascade');
            $table->foreignId('review_id')->index()->constrained()->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_likes');
        Schema::dropIfExists('event_likes');
        Schema::dropIfExists('booth_likes');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('user_event_information');
        Schema::dropIfExists('user_event_surveys');
        Schema::dropIfExists('user_events');
    }
};
