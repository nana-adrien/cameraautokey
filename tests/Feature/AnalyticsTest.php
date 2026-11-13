<?php

namespace Tests\Feature;

use App\Models\AnalyticsEvent;
use App\Models\AnalyticsPageView;
use App\Models\AnalyticsVisit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class AnalyticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_page_records_visit_and_page_view(): void
    {
        config(['analytics.enabled' => true]);

        $response = $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0 Safari/537.36',
            'X-Vercel-IP-Country' => 'CM',
            'X-Vercel-IP-City' => 'Douala',
        ])->get('/');

        $response->assertOk();
        $this->assertDatabaseCount('analytics_visits', 1);
        $this->assertDatabaseCount('analytics_page_views', 1);

        $visit = AnalyticsVisit::first();
        $this->assertSame('CM', $visit->country_code);
        $this->assertSame('Douala', $visit->city);
        $this->assertSame('desktop', $visit->device);
        $this->assertSame('Chrome', $visit->browser);
        $this->assertSame('macOS', $visit->os);
    }

    public function test_public_page_exposes_analytics_config_to_javascript(): void
    {
        config(['analytics.enabled' => true]);

        $response = $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0 Safari/537.36',
        ])->get('/');

        $response->assertOk();

        $visit = AnalyticsVisit::firstOrFail();
        $pageView = AnalyticsPageView::firstOrFail();

        $response->assertSee('window.__CAK_ANALYTICS', false);
        $response->assertSee('"visit":"'.$visit->uuid.'"', false);
        $response->assertSee('"pageView":'.$pageView->id, false);
        $response->assertSee('analytics.js', false);
    }

    public function test_bot_does_not_receive_analytics_script(): void
    {
        config(['analytics.enabled' => true]);

        $response = $this->withHeaders([
            'User-Agent' => 'Googlebot/2.1 (+http://www.google.com/bot.html)',
        ])->get('/');

        $response->assertOk();
        $response->assertDontSee('window.__CAK_ANALYTICS', false);
        $this->assertDatabaseCount('analytics_visits', 0);
    }

    public function test_track_endpoint_records_form_submit_and_conversion(): void
    {
        $visit = $this->makeVisit();

        $response = $this->post('/_track', [
            'visit' => $visit->uuid,
            'event' => 'form_submit',
            'step' => 3,
            'form' => 'order',
        ]);

        $response->assertNoContent();
        $this->assertDatabaseHas('analytics_events', [
            'visit_id' => $visit->id,
            'name' => 'form_submit',
            'step' => 3,
        ]);
        $this->assertTrue($visit->fresh()->converted);
    }

    public function test_track_endpoint_updates_page_duration(): void
    {
        $visit = $this->makeVisit();
        $pageView = AnalyticsPageView::create([
            'visit_id' => $visit->id,
            'path' => '/',
            'viewed_at' => now(),
        ]);

        $this->post('/_track', [
            'visit' => $visit->uuid,
            'event' => 'page_leave',
            'page_view' => $pageView->id,
            'duration_ms' => 4200,
        ])->assertNoContent();

        $this->assertSame(4200, $pageView->fresh()->duration_ms);
    }

    public function test_unknown_visit_is_ignored(): void
    {
        $this->post('/_track', [
            'visit' => (string) Str::uuid(),
            'event' => 'form_start',
        ])->assertNoContent();

        $this->assertDatabaseCount('analytics_events', 0);
    }

    public function test_cron_prune_requires_secret(): void
    {
        config(['services.cron.secret' => 'secret']);

        $this->get(route('cron.analytics-prune'))->assertForbidden();
        $this->withHeader('Authorization', 'Bearer wrong')->get(route('cron.analytics-prune'))->assertForbidden();
    }

    public function test_cron_prune_deletes_old_visits(): void
    {
        config(['services.cron.secret' => 'secret']);

        $old = $this->makeVisit();
        $old->update(['started_at' => now()->subDays(400)]);

        $recent = $this->makeVisit();

        $this->withHeader('Authorization', 'Bearer secret')
            ->get(route('cron.analytics-prune'))
            ->assertNoContent();

        $this->assertDatabaseMissing('analytics_visits', ['id' => $old->id]);
        $this->assertDatabaseHas('analytics_visits', ['id' => $recent->id]);
    }

    public function test_admin_can_open_analytics_dashboard(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $visit = $this->makeVisit();
        AnalyticsPageView::create([
            'visit_id' => $visit->id,
            'path' => '/',
            'duration_ms' => 3000,
            'viewed_at' => now(),
        ]);
        AnalyticsEvent::create(['visit_id' => $visit->id, 'name' => 'form_start', 'occurred_at' => now()]);
        AnalyticsEvent::create(['visit_id' => $visit->id, 'name' => 'form_step', 'step' => 2, 'occurred_at' => now()]);
        AnalyticsEvent::create(['visit_id' => $visit->id, 'name' => 'form_submit', 'step' => 3, 'occurred_at' => now()]);

        $response = $this->actingAs($admin)->get(route('admin.analytics'));

        $response->assertOk();
        $response->assertSee('Statistiques visiteurs');
        $response->assertSee('Taux de conversion');
    }

    private function makeVisit(): AnalyticsVisit
    {
        return AnalyticsVisit::create([
            'uuid' => (string) Str::uuid(),
            'session_id' => 'test-session',
            'country_code' => 'CM',
            'device' => 'desktop',
            'browser' => 'Chrome',
            'os' => 'macOS',
            'landing_path' => '/',
            'started_at' => now(),
            'last_seen_at' => now(),
        ]);
    }
}
