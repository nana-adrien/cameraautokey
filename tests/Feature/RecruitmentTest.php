<?php

namespace Tests\Feature;

use App\Models\JobApplication;
use App\Models\JobOffer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RecruitmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_offers_page_lists_open_offers_and_filters_by_company(): void
    {
        JobOffer::factory()->create(['company' => 'cak', 'title' => 'Technicien clés']);
        JobOffer::factory()->create(['company' => '3k', 'title' => 'Mécanicien']);
        JobOffer::factory()->closed()->create(['company' => '3k', 'title' => 'Offre expirée']);
        JobOffer::factory()->create(['company' => '3k', 'title' => 'Offre brouillon', 'is_published' => false]);

        $this->get(route('careers.index'))
            ->assertOk()
            ->assertSee('Technicien clés')
            ->assertSee('Mécanicien')
            ->assertDontSee('Offre expirée')
            ->assertDontSee('Offre brouillon');

        $this->get(route('careers.index', ['entreprise' => '3k']))
            ->assertOk()
            ->assertSee('Mécanicien')
            ->assertDontSee('Technicien clés');
    }

    public function test_closed_offer_cannot_be_viewed_or_applied_to(): void
    {
        $offer = JobOffer::factory()->closed()->create();

        $this->get(route('careers.show', $offer))->assertNotFound();
        $this->post(route('careers.apply', $offer), $this->payload())->assertNotFound();
    }

    public function test_application_stores_cv_and_sends_it_to_telegram(): void
    {
        Storage::fake();
        Http::fake();
        config(['services.telegram.bot_token' => 'test-token', 'services.telegram.chat_id' => '123456']);
        $offer = JobOffer::factory()->create();

        $this->get(route('careers.show', $offer))->assertOk()->assertSee($offer->title);

        $this->post(route('careers.apply', $offer), $this->payload())
            ->assertRedirect()
            ->assertSessionHas('success');

        $application = JobApplication::sole();
        $this->assertSame($offer->id, $application->job_offer_id);
        $this->assertSame('mon-cv.pdf', $application->cv_original_name);
        Storage::assertExists($application->cv_path);
        Http::assertSent(fn ($request) => str_contains($request->url(), '/bottest-token/sendDocument'));
    }

    public function test_application_rejects_executable_files_and_missing_consent(): void
    {
        Storage::fake();
        $offer = JobOffer::factory()->create();

        $this->post(route('careers.apply', $offer), $this->payload([
            'cv' => UploadedFile::fake()->create('cv.php', 10, 'application/x-php'),
            'accept_privacy' => null,
        ]))->assertSessionHasErrors(['cv', 'accept_privacy']);

        $this->assertDatabaseCount('job_applications', 0);
    }

    public function test_admin_can_review_application_and_download_cv(): void
    {
        Storage::fake();
        Storage::put('cvs/cv-test.pdf', '%PDF-1.4 test');
        $application = JobApplication::factory()->create(['cv_path' => 'cvs/cv-test.pdf', 'cv_original_name' => 'cv-jean.pdf']);
        $this->actingAs(User::factory()->create(['is_admin' => true]));

        $this->get(route('admin.applications.index'))->assertOk()->assertSee($application->name);
        $this->get(route('admin.applications.show', $application))->assertOk()->assertSee('cv-jean.pdf');
        $this->assertNotNull($application->fresh()->read_at);

        $this->get(route('admin.applications.cv', $application))->assertDownload('cv-jean.pdf');

        $this->put(route('admin.applications.update', $application), ['status' => 'interview'])->assertRedirect();
        $this->assertSame('interview', $application->fresh()->status);
    }

    public function test_guest_cannot_access_applications_or_cvs(): void
    {
        $application = JobApplication::factory()->create();

        $this->get(route('admin.applications.index'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.applications.cv', $application))->assertRedirect(route('admin.login'));
    }

    public function test_locked_recruitment_shows_development_page(): void
    {
        config(['demo.locked' => ['recruitment']]);
        JobOffer::factory()->create(['title' => 'Mécanicien']);

        $this->get(route('careers.index'))
            ->assertOk()
            ->assertSee('Espace recrutement')
            ->assertDontSee('Mécanicien');
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Jean Test',
            'email' => 'jean@example.com',
            'phone' => '+237 600 00 00 00',
            'message' => 'Mécanicien depuis 5 ans.',
            'cv' => UploadedFile::fake()->create('mon-cv.pdf', 200, 'application/pdf'),
            'accept_privacy' => 1,
        ], $overrides);
    }
}
