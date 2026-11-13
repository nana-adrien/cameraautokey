<?php

namespace App\Http\Controllers;

use App\Models\JobOffer;
use App\Services\TelegramNotifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CareerController extends Controller
{
    public function index(Request $request): View
    {
        $company = $request->string('entreprise')->toString();

        $offers = JobOffer::query()
            ->open()
            ->when(array_key_exists($company, JobOffer::COMPANIES), fn ($q) => $q->where('company', $company))
            ->latest()
            ->get();

        return view('pages.careers.index', compact('offers', 'company'));
    }

    public function show(JobOffer $jobOffer): View
    {
        abort_unless(JobOffer::query()->open()->whereKey($jobOffer->id)->exists(), 404);

        return view('pages.careers.show', ['offer' => $jobOffer]);
    }

    public function apply(Request $request, JobOffer $jobOffer, TelegramNotifier $telegram): RedirectResponse
    {
        abort_unless(JobOffer::query()->open()->whereKey($jobOffer->id)->exists(), 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'message' => ['nullable', 'string', 'max:3000'],
            'cv' => ['required', 'file', 'mimes:pdf,doc,docx', 'extensions:pdf,doc,docx', 'max:5120'],
            'accept_privacy' => ['accepted'],
        ], [], ['cv' => 'CV', 'accept_privacy' => 'consentement']);

        $cv = $request->file('cv');

        $application = $jobOffer->applications()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'message' => $data['message'] ?? null,
            'cv_path' => $cv->store('cvs'),
            'cv_original_name' => $cv->getClientOriginalName(),
        ]);

        $telegram->sendJobApplication($application);

        return back()->with('success', 'Merci ! Votre candidature a bien été envoyée. Nous vous recontactons si votre profil est retenu.');
    }
}
