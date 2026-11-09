<?php

namespace App\Http\Controllers\Admin;

use App\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class ServiceController extends CrudController
{
    protected string $model = Service::class;

    protected string $routeName = 'services';

    protected string $title = 'Services';

    protected string $singular = 'Service';

    protected array $orderBy = ['position', 'asc'];

    protected function columns(): array
    {
        return ['title' => 'Titre', 'icon' => 'Icône', 'price_from' => 'À partir de (FCFA)', 'position' => 'Ordre', 'is_active' => 'Actif'];
    }

    protected function fields(?Model $record = null): array
    {
        return [
            'title' => ['label' => 'Titre', 'type' => 'text', 'rules' => ['required', 'string', 'max:150']],
            'slug' => ['label' => 'Slug (URL)', 'type' => 'text', 'rules' => ['nullable', 'alpha_dash', 'max:150', Rule::unique('services')->ignore($record?->id)], 'help' => 'Laisser vide pour le générer automatiquement.'],
            'icon' => ['label' => 'Icône (emoji)', 'type' => 'text', 'rules' => ['required', 'string', 'max:10']],
            'excerpt' => ['label' => 'Résumé', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:500']],
            'content' => ['label' => 'Contenu', 'type' => 'textarea', 'rules' => ['required', 'string']],
            'price_from' => ['label' => 'Prix à partir de (FCFA)', 'type' => 'number', 'rules' => ['required', 'integer', 'min:0']],
            'position' => ['label' => 'Ordre d’affichage', 'type' => 'number', 'rules' => ['required', 'integer', 'min:0']],
            'is_active' => ['label' => 'Actif', 'type' => 'checkbox', 'rules' => ['boolean']],
        ];
    }
}
