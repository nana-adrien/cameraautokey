<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobOffer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class JobApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $applications = JobApplication::query()
            ->with('jobOffer')
            ->when($request->filled('offre'), fn ($q) => $q->where('job_offer_id', $request->integer('offre')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $offers = JobOffer::query()->orderBy('title')->get(['id', 'title', 'company']);

        return view('admin.applications.index', compact('applications', 'offers'));
    }

    public function show(JobApplication $application): View
    {
        if (! $application->read_at) {
            $application->update(['read_at' => now()]);
        }

        return view('admin.applications.show', ['application' => $application->load('jobOffer')]);
    }

    public function update(Request $request, JobApplication $application): RedirectResponse
    {
        $application->update($request->validate([
            'status' => ['required', Rule::in(array_keys(JobApplication::STATUSES))],
        ]));

        return back()->with('success', 'Statut mis à jour.');
    }

    public function cv(JobApplication $application): StreamedResponse
    {
        abort_unless(Storage::exists($application->cv_path), 404);

        return Storage::download($application->cv_path, $application->cv_original_name);
    }

    public function destroy(JobApplication $application): RedirectResponse
    {
        Storage::delete($application->cv_path);
        $application->delete();

        return redirect()->route('admin.applications.index')->with('success', 'Candidature supprimée.');
    }
}
