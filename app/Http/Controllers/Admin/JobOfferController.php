<?php

namespace App\Http\Controllers\Admin;

use App\Models\JobOffer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class JobOfferController extends CrudController
{
    protected string $model = JobOffer::class;

    protected string $routeName = 'job-offers';

    protected string $title = 'Offres d’emploi';

    protected string $singular = 'Offre';

    protected function columns(): array
    {
        return ['title' => 'Poste', 'company_name' => 'Entreprise', 'location' => 'Lieu', 'is_published' => 'Publiée'];
    }

    protected function fields(?Model $record = null): array
    {
        return [
            'company' => ['label' => 'Entreprise', 'type' => 'select', 'options' => JobOffer::COMPANIES, 'rules' => ['required', Rule::in(array_keys(JobOffer::COMPANIES))]],
            'title' => ['label' => 'Intitulé du poste', 'type' => 'text', 'rules' => ['required', 'string', 'max:150']],
            'slug' => ['label' => 'Adresse (slug)', 'type' => 'text', 'rules' => ['nullable', 'alpha_dash', 'max:160', Rule::unique('job_offers', 'slug')->ignore($record?->id)], 'help' => 'Laisser vide pour la générer à partir de l’intitulé.'],
            'contract_type' => ['label' => 'Type de contrat', 'type' => 'text', 'rules' => ['required', 'string', 'max:60'], 'help' => 'Ex. CDI, CDD, Stage, Prestataire.'],
            'location' => ['label' => 'Lieu', 'type' => 'text', 'rules' => ['required', 'string', 'max:100']],
            'summary' => ['label' => 'Résumé (affiché dans la liste)', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:500']],
            'description' => ['label' => 'Missions', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:5000'], 'help' => 'Une mission par ligne.'],
            'requirements' => ['label' => 'Profil recherché', 'type' => 'textarea', 'rules' => ['nullable', 'string', 'max:3000'], 'help' => 'Une exigence par ligne.'],
            'closes_at' => ['label' => 'Date limite de candidature', 'type' => 'date', 'rules' => ['nullable', 'date']],
            'is_published' => ['label' => 'Publiée', 'type' => 'checkbox', 'rules' => ['boolean']],
        ];
    }
}
