<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Contrôleur CRUD générique du dashboard : chaque ressource déclare son modèle,
 * ses champs de formulaire et ses colonnes, et réutilise les vues admin/crud/*.
 */
abstract class CrudController extends Controller
{
    /** @var class-string<Model> */
    protected string $model;

    protected string $routeName;

    protected string $title;

    protected string $singular;

    /** Ordre d'affichage de la liste. */
    protected array $orderBy = ['id', 'desc'];

    /**
     * @return array<string, array{label:string, type:string, rules:array, options?:array, help?:string}>
     */
    abstract protected function fields(?Model $record = null): array;

    /** @return array<string, string> colonne => libellé */
    abstract protected function columns(): array;

    public function index(Request $request): View
    {
        $query = $this->model::query()->orderBy(...$this->orderBy);

        if ($request->filled('q')) {
            $first = array_key_first($this->columns());
            $query->where($first, 'like', '%'.$request->string('q').'%');
        }

        return view('admin.crud.index', [
            'records' => $query->paginate(20)->withQueryString(),
            'columns' => $this->columns(),
            'fields' => $this->fields(),
            'routeName' => $this->routeName,
            'title' => $this->title,
            'singular' => $this->singular,
        ]);
    }

    public function create(): View
    {
        return $this->form(new $this->model);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->model::create($this->validated($request));

        return redirect()->route("admin.{$this->routeName}.index")->with('success', "{$this->singular} créé(e).");
    }

    public function edit(string $id): View
    {
        return $this->form($this->model::findOrFail($id));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $record = $this->model::findOrFail($id);
        $record->update($this->validated($request, $record));

        return redirect()->route("admin.{$this->routeName}.index")->with('success', "{$this->singular} mis(e) à jour.");
    }

    public function destroy(string $id): RedirectResponse
    {
        $this->model::findOrFail($id)->delete();

        return back()->with('success', "{$this->singular} supprimé(e).");
    }

    protected function form(Model $record): View
    {
        return view('admin.crud.form', [
            'record' => $record,
            'fields' => $this->fields($record),
            'routeName' => $this->routeName,
            'title' => $this->title,
            'singular' => $this->singular,
        ]);
    }

    protected function validated(Request $request, ?Model $record = null): array
    {
        $fields = $this->fields($record);
        $data = $request->validate(
            collect($fields)->map(fn ($f) => $f['rules'])->all(),
            [],
            collect($fields)->map(fn ($f) => Str::lower($f['label']))->all(),
        );

        foreach ($fields as $name => $field) {
            if ($field['type'] === 'checkbox') {
                $data[$name] = $request->boolean($name);
            }
        }

        if (array_key_exists('slug', $fields) && empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name'] ?? $data['title'] ?? Str::random(8));
        }

        return $data;
    }
}
