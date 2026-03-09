<section class="panel">
        <div class="panel-head">
            <div>
                <h3>{{ $moduleTitle }}</h3>
                <p>{{ $moduleDescription }}</p>
            </div>
            <div class="feedback-row">
                <p class="feedback info">{{ $records->count() }} ligne(s) affichee(s)</p>
            </div>
        </div>

        @if(session('status'))
            <p class="feedback success">{{ session('status') }}</p>
        @endif

        @if($canManage)
            <form
                method="POST"
                action="{{ $editingRecord ? route('module.update', ['module' => $currentModule, 'id' => $editingRecord->id]) : route('module.store', ['module' => $currentModule]) }}"
                class="crud-form"
            >
                @csrf
                @if($editingRecord)
                    @method('PUT')
                @endif

                <div class="crud-grid">
                    @foreach($editableColumns as $column)
                        <div class="field">
                            <label for="{{ $column }}">{{ str_replace('_', ' ', ucfirst($column)) }}</label>
                            @if(isset($enumOptions[$column]))
                                @php($currentValue = old($column, $editingRecord->{$column} ?? ''))
                                <select id="{{ $column }}" name="{{ $column }}">
                                    <option value="">Selectionner</option>
                                    @foreach($enumOptions[$column] as $option)
                                        <option value="{{ $option }}" @selected((string) $currentValue === (string) $option)>{{ $option }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input
                                    id="{{ $column }}"
                                    name="{{ $column }}"
                                    type="text"
                                    value="{{ old($column, $editingRecord->{$column} ?? '') }}"
                                >
                            @endif
                            @error($column)<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                    @endforeach
                </div>

                <div class="actions-row">
                    <button type="submit" class="btn-primary">
                        {{ $editingRecord ? 'Mettre a jour' : 'Ajouter' }}
                    </button>
                    @if($editingRecord)
                        <a href="{{ route('module.show', ['module' => $currentModule]) }}" class="btn-secondary">Annuler</a>
                    @endif
                </div>
            </form>
        @endif
    </section>

<section class="panel">
        <div class="panel-head">
            <div>
                <h3>Liste</h3>
                <p>Donnees module</p>
            </div>
        </div>

        @if($records->isEmpty())
            <div class="state-card">
                <h4>Aucune donnee</h4>
                <p>Aucune ligne disponible pour ce module.</p>
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            @foreach($displayColumns as $column)
                                <th>{{ str_replace('_', ' ', ucfirst($column)) }}</th>
                            @endforeach
                            @if($canManage)
                                <th>Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $row)
                            <tr>
                                @foreach($displayColumns as $column)
                                    <td>{{ \Illuminate\Support\Str::limit((string) data_get($row, $column), 80) }}</td>
                                @endforeach
                                @if($canManage)
                                    <td>
                                        <div class="row-actions">
                                            <a
                                                href="{{ route('module.show', ['module' => $currentModule, 'edit' => $row->id]) }}"
                                                class="btn-icon"
                                            >
                                                Editer
                                            </a>
                                            <form
                                                method="POST"
                                                action="{{ route('module.destroy', ['module' => $currentModule, 'id' => $row->id]) }}"
                                                onsubmit="return confirm('Supprimer cet enregistrement ?');"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-icon danger">Supprimer</button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
