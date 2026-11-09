<?php

namespace App\Http\Controllers\Admin;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Model;

class FaqController extends CrudController
{
    protected string $model = Faq::class;

    protected string $routeName = 'faqs';

    protected string $title = 'FAQ';

    protected string $singular = 'Question';

    protected array $orderBy = ['position', 'asc'];

    protected function columns(): array
    {
        return ['question' => 'Question', 'position' => 'Ordre', 'is_published' => 'Publiée'];
    }

    protected function fields(?Model $record = null): array
    {
        return [
            'question' => ['label' => 'Question', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
            'answer' => ['label' => 'Réponse', 'type' => 'textarea', 'rules' => ['required', 'string', 'max:3000']],
            'position' => ['label' => 'Ordre d’affichage', 'type' => 'number', 'rules' => ['required', 'integer', 'min:0']],
            'is_published' => ['label' => 'Publiée', 'type' => 'checkbox', 'rules' => ['boolean']],
        ];
    }
}
