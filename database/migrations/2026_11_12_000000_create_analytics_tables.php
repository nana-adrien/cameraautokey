<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analytics_visits', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index();
            $table->string('session_id')->nullable()->index();
            $table->string('ip_hash', 64)->nullable();
            $table->string('country_code', 2)->nullable()->index();
            $table->string('city')->nullable();
            $table->string('device', 20)->nullable()->index();
            $table->string('browser', 30)->nullable();
            $table->string('os', 30)->nullable();
            $table->string('referrer_host')->nullable();
            $table->string('landing_path')->nullable();
            $table->string('user_agent', 512)->nullable();
            $table->timestamp('started_at')->index();
            $table->timestamp('last_seen_at')->index();
            $table->boolean('converted')->default(false)->index();
            $table->unsignedTinyInteger('last_step')->nullable();
            $table->timestamp('converted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('analytics_page_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained('analytics_visits')->cascadeOnDelete();
            $table->string('path')->index();
            $table->string('route_name')->nullable()->index();
            $table->string('title')->nullable();
            $table->string('referrer_host')->nullable();
            $table->unsignedInteger('duration_ms')->nullable();
            $table->timestamp('viewed_at')->index();
            $table->timestamps();
        });

        Schema::create('analytics_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained('analytics_visits')->cascadeOnDelete();
            $table->string('name', 40)->index();
            $table->unsignedTinyInteger('step')->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('occurred_at')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_events');
        Schema::dropIfExists('analytics_page_views');
        Schema::dropIfExists('analytics_visits');
    }
};
