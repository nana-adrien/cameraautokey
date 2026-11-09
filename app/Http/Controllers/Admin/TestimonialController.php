<?php

namespace App\Http\Controllers\Admin;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Model;

class TestimonialController extends CrudController
{
    protected string $model = Testimonial::class;

    protected string $routeName = 'testimonials';

    protected string $title = 'Avis clients';

    protected string $singular = 'Avis';

    protected function columns(): array
    {
        return ['name' => 'Client', 'city' => 'Ville', 'rating' => 'Note', 'is_published' => 'Publié'];
    }

    protected function fields(?Model $record = null): array
    {
        return [
            'name' => ['label' => 'Nom du client', 'type' => 'text', 'rules' => ['required', 'string', 'max:100']],
            'city' => ['label' => 'Ville', 'type' => 'text', 'rules' => ['required', 'string', 'max:100']],
            'content' => ['label' => 'Avis', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:1000']],
            'rating' => ['label' => 'Note (1 à 5)', 'type' => 'number', 'rules' => ['required', 'integer', 'between:1,5']],
            'is_published' => ['label' => 'Publié', 'type' => 'checkbox', 'rules' => ['boolean']],
        ];
    }
}
