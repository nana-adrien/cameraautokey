<?php

namespace App\Http\Controllers\Admin;

use App\Models\Technician;
use Illuminate\Database\Eloquent\Model;

class TechnicianController extends CrudController
{
    protected string $model = Technician::class;

    protected string $routeName = 'technicians';

    protected string $title = 'Techniciens';

    protected string $singular = 'Technicien';

    protected array $orderBy = ['name', 'asc'];

    protected function columns(): array
    {
        return ['name' => 'Nom', 'phone' => 'Téléphone', 'email' => 'E-mail', 'zone' => 'Zone', 'is_active' => 'Actif'];
    }

    protected function fields(?Model $record = null): array
    {
        return [
            'name' => ['label' => 'Nom', 'type' => 'text', 'rules' => ['required', 'string', 'max:100']],
            'phone' => ['label' => 'Téléphone', 'type' => 'text', 'rules' => ['required', 'string', 'max:30']],
            'email' => ['label' => 'E-mail', 'type' => 'email', 'rules' => ['nullable', 'email', 'max:150']],
            'zone' => ['label' => 'Zone d’intervention', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:100']],
            'is_active' => ['label' => 'Actif', 'type' => 'checkbox', 'rules' => ['boolean']],
        ];
    }
}
