<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class BrandController extends CrudController
{
    protected string $model = Brand::class;

    protected string $routeName = 'brands';

    protected string $title = 'Marques';

    protected string $singular = 'Marque';

    protected array $orderBy = ['name', 'asc'];

    protected function columns(): array
    {
        return ['name' => 'Nom', 'category' => 'Catégorie', 'slug' => 'Slug', 'is_active' => 'Active'];
    }

    protected function fields(?Model $record = null): array
    {
        return [
            'name' => ['label' => 'Nom', 'type' => 'text', 'rules' => ['required', 'string', 'max:100']],
            'slug' => ['label' => 'Slug (URL)', 'type' => 'text', 'rules' => ['nullable', 'alpha_dash', 'max:100', Rule::unique('brands')->ignore($record?->id)], 'help' => 'Laisser vide pour le générer automatiquement.'],
            'category' => ['label' => 'Catégorie', 'type' => 'select', 'options' => Brand::CATEGORIES, 'rules' => ['required', Rule::in(array_keys(Brand::CATEGORIES))]],
            'description' => ['label' => 'Description', 'type' => 'textarea', 'rules' => ['nullable', 'string', 'max:2000']],
            'is_active' => ['label' => 'Active', 'type' => 'checkbox', 'rules' => ['boolean']],
        ];
    }
}
