<div class="crud-grid">
    <div class="field">
        <label for="nom">Nom</label>
        <input id="nom" name="nom" type="text" value="{{ old('nom', $employe->nom ?? '') }}" required>
        @error('nom')<p class="field-error">{{ $message }}</p>@enderror
    </div>

    <div class="field">
        <label for="prenom">Prenom</label>
        <input id="prenom" name="prenom" type="text" value="{{ old('prenom', $employe->prenom ?? '') }}" required>
        @error('prenom')<p class="field-error">{{ $message }}</p>@enderror
    </div>

    <div class="field">
        <label for="poste">Poste</label>
        <input id="poste" name="poste" type="text" value="{{ old('poste', $employe->poste ?? '') }}">
        @error('poste')<p class="field-error">{{ $message }}</p>@enderror
    </div>

    <div class="field">
        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email', $employe->email ?? '') }}">
        @error('email')<p class="field-error">{{ $message }}</p>@enderror
    </div>

    <div class="field">
        <label for="adresse">Adresse</label>
        <input id="adresse" name="adresse" type="text" value="{{ old('adresse', $employe->adresse ?? '') }}">
        @error('adresse')<p class="field-error">{{ $message }}</p>@enderror
    </div>

    <div class="field">
        <label for="salaire">Salaire</label>
        <input id="salaire" name="salaire" type="number" step="0.01" min="0" value="{{ old('salaire', $employe->salaire ?? '') }}">
        @error('salaire')<p class="field-error">{{ $message }}</p>@enderror
    </div>

    <div class="field">
        <label for="date_embauche">Date embauche</label>
        <input id="date_embauche" name="date_embauche" type="date" value="{{ old('date_embauche', $employe->date_embauche ?? '') }}">
        @error('date_embauche')<p class="field-error">{{ $message }}</p>@enderror
    </div>

    <div class="field">
        <label for="contrat">Contrat</label>
        <select id="contrat" name="contrat" required>
            @php($currentContrat = old('contrat', $employe->contrat ?? 'cdi'))
            <option value="stage" @selected($currentContrat === 'stage')>Stage</option>
            <option value="cdd" @selected($currentContrat === 'cdd')>CDD</option>
            <option value="cdi" @selected($currentContrat === 'cdi')>CDI</option>
        </select>
        @error('contrat')<p class="field-error">{{ $message }}</p>@enderror
    </div>
</div>

<div class="actions-row">
    <button type="submit" class="btn-primary">{{ $submitLabel }}</button>
    <a href="{{ route('employes.index') }}" class="btn-secondary">Annuler</a>
</div>
