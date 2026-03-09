<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ModuleController extends Controller
{
    public function show(Request $request, string $module): View
    {
        $config = $this->moduleConfig($module);
        $this->ensureCanView($module, $config);

        $columnsMeta = DB::select("SHOW COLUMNS FROM {$config['table']}");
        $displayColumns = collect($columnsMeta)
            ->pluck('Field')
            ->reject(fn (string $field) => in_array($field, ['created_at', 'updated_at'], true))
            ->values()
            ->all();

        $editableColumns = collect($displayColumns)
            ->reject(fn (string $field) => $field === 'id')
            ->values()
            ->all();

        $enumOptions = $this->extractEnumOptions($columnsMeta);

        $records = DB::table($config['table'])
            ->orderByDesc('id')
            ->limit(100)
            ->get();

        $editingId = $request->integer('edit');
        $editingRecord = null;

        if ($editingId > 0) {
            $editingRecord = DB::table($config['table'])->where('id', $editingId)->first();
        }

        return view("modules.{$module}.index", [
            'currentModule' => $module,
            'moduleTitle' => $config['title'],
            'moduleDescription' => $config['description'],
            'modules' => $this->modules(),
            'records' => $records,
            'displayColumns' => $displayColumns,
            'editableColumns' => $editableColumns,
            'enumOptions' => $enumOptions,
            'editingRecord' => $editingRecord,
            'canManage' => $this->canManage($module, $config),
        ]);
    }

    public function store(Request $request, string $module): RedirectResponse
    {
        $config = $this->moduleConfig($module);
        $this->ensureCanManage($module, $config);

        $columnsMeta = DB::select("SHOW COLUMNS FROM {$config['table']}");
        $rules = $this->buildRules($columnsMeta, false);
        $data = $request->validate($rules);

        DB::table($config['table'])->insert($data);

        return redirect()
            ->route('module.show', ['module' => $module])
            ->with('status', 'Enregistrement ajoute avec succes.');
    }

    public function update(Request $request, string $module, int $id): RedirectResponse
    {
        $config = $this->moduleConfig($module);
        $this->ensureCanManage($module, $config);

        abort_unless(DB::table($config['table'])->where('id', $id)->exists(), 404);

        $columnsMeta = DB::select("SHOW COLUMNS FROM {$config['table']}");
        $rules = $this->buildRules($columnsMeta, true);
        $data = $request->validate($rules);

        DB::table($config['table'])->where('id', $id)->update($data);

        return redirect()
            ->route('module.show', ['module' => $module])
            ->with('status', 'Enregistrement modifie avec succes.');
    }

    public function destroy(string $module, int $id): RedirectResponse
    {
        $config = $this->moduleConfig($module);
        $this->ensureCanManage($module, $config);

        DB::table($config['table'])->where('id', $id)->delete();

        return redirect()
            ->route('module.show', ['module' => $module])
            ->with('status', 'Enregistrement supprime avec succes.');
    }

    private function ensureCanView(string $module, array $config): void
    {
        if ($this->canView($module, $config)) {
            return;
        }

        abort(403, "Vous n'avez pas acces a ce module.");
    }

    private function ensureCanManage(string $module, array $config): void
    {
        if ($this->canManage($module, $config)) {
            return;
        }

        abort(403, "Vous n'avez pas acces a cette action.");
    }

    private function canView(string $module, array $config): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        $viewPermission = $config['view_permission'];
        $managePermission = $config['manage_permission'];

        return ($viewPermission && $user->hasPermission($viewPermission))
            || ($managePermission && $user->hasPermission($managePermission));
    }

    private function canManage(string $module, array $config): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        return (bool) ($config['manage_permission'] && $user->hasPermission($config['manage_permission']));
    }

    private function moduleConfig(string $module): array
    {
        $config = $this->modules()[$module] ?? null;
        abort_unless($config, 404);
        abort_unless(Schema::hasTable($config['table']), 404);

        return $config;
    }

    private function modules(): array
    {
        return [
            'clients' => [
                'title' => 'Clients',
                'description' => 'Suivi des profils clients, contacts et historique des services.',
                'table' => 'clients',
                'view_permission' => null,
                'manage_permission' => 'manage_clients',
            ],
            'fournisseurs' => [
                'title' => 'Fournisseurs',
                'description' => 'Gestion des partenaires, contrats et conditions d achat.',
                'table' => 'fournisseurs',
                'view_permission' => 'view_fournisseurs',
                'manage_permission' => 'manage_fournisseurs',
            ],
            'voitures' => [
                'title' => 'Voitures',
                'description' => 'Inventaire complet des vehicules, etats et disponibilites.',
                'table' => 'voitures',
                'view_permission' => null,
                'manage_permission' => 'manage_voitures',
            ],
            'facturations' => [
                'title' => 'Facturations',
                'description' => 'Suivi des factures, statuts de paiement et echeances.',
                'table' => 'facturations',
                'view_permission' => null,
                'manage_permission' => 'manage_factures',
            ],
            'paiements' => [
                'title' => 'Paiements',
                'description' => 'Historique des reglements, moyens de paiement et validations.',
                'table' => 'paiements',
                'view_permission' => null,
                'manage_permission' => 'manage_paiements',
            ],
            'garanties' => [
                'title' => 'Garanties',
                'description' => 'Controle des couvertures, dates d expiration et litiges.',
                'table' => 'garanties',
                'view_permission' => null,
                'manage_permission' => 'manage_voitures',
            ],
            'documents' => [
                'title' => 'Documents',
                'description' => 'Centralisation des pieces administratives et contrats.',
                'table' => 'documents',
                'view_permission' => null,
                'manage_permission' => 'manage_voitures',
            ],
            'categorie_voiture' => [
                'title' => 'Categorie voiture',
                'description' => 'Classification des vehicules par segment et usage.',
                'table' => 'types_vehicules',
                'view_permission' => null,
                'manage_permission' => 'manage_voitures',
            ],
        ];
    }

    private function buildRules(array $columnsMeta, bool $updating): array
    {
        $rules = [];

        foreach ($columnsMeta as $column) {
            $field = $column->Field;
            $type = strtolower((string) $column->Type);
            $nullable = strtoupper((string) $column->Null) === 'YES';

            if (in_array($field, ['id', 'created_at', 'updated_at'], true)) {
                continue;
            }

            $fieldRules = [];
            $fieldRules[] = $nullable ? 'nullable' : 'required';

            if (Str::startsWith($type, 'enum(')) {
                preg_match_all("/'([^']+)'/", $type, $matches);
                $allowed = $matches[1] ?? [];
                $fieldRules[] = 'in:'.implode(',', $allowed);
            } elseif (str_contains($type, 'int')) {
                $fieldRules[] = 'integer';
            } elseif (str_contains($type, 'decimal') || str_contains($type, 'float') || str_contains($type, 'double')) {
                $fieldRules[] = 'numeric';
            } elseif (str_contains($type, 'date') || str_contains($type, 'year')) {
                $fieldRules[] = 'date';
            } elseif (preg_match('/varchar\((\d+)\)/', $type, $matches)) {
                $fieldRules[] = 'string';
                $fieldRules[] = 'max:'.$matches[1];
            } else {
                $fieldRules[] = 'string';
            }

            if ($updating && ! $nullable) {
                $fieldRules = array_values(array_diff($fieldRules, ['required']));
                array_unshift($fieldRules, 'sometimes');
            }

            $rules[$field] = $fieldRules;
        }

        return $rules;
    }

    private function extractEnumOptions(array $columnsMeta): array
    {
        $enumOptions = [];

        foreach ($columnsMeta as $column) {
            $type = strtolower((string) $column->Type);
            if (! Str::startsWith($type, 'enum(')) {
                continue;
            }

            preg_match_all("/'([^']+)'/", $type, $matches);
            $enumOptions[$column->Field] = $matches[1] ?? [];
        }

        return $enumOptions;
    }
}
