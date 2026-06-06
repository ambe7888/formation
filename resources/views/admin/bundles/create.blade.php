@extends('admin.layout')

@section('title', 'Créer un Pack / Bundle')

@section('content')
<div class="card card-borderless p-4">
    @if($trainings->count() < 2)
        <div class="alert alert-warning">
            Vous devez disposer d'au moins 2 formations actives pour créer un pack.
            <a href="{{ route('admin.trainings.create') }}" style="color:inherit;font-weight:600;">Ajouter une formation active</a>.
        </div>
    @endif

    <form action="{{ route('admin.bundles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Infos de base --}}
        <div style="display:grid;grid-template-columns:1fr auto;gap:1.25rem;align-items:start;margin-bottom:1.25rem;">
            <div>
                <label for="name" class="form-label">Nom du Pack *</label>
                <input type="text" class="form-control" id="name" name="name"
                       value="{{ old('name') }}" placeholder="ex : Pack Complet IA & Marketing" required>
            </div>
            <div style="min-width:180px;">
                <label for="price" class="form-label">Prix Spécial Bundle (CFA) *</label>
                <input type="number" class="form-control" id="price" name="price"
                       value="{{ old('price') }}" placeholder="ex : 25000" min="0" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description du Pack</label>
            <textarea class="form-control" id="description" name="description" rows="3"
                       placeholder="Présentez les avantages de ce pack groupé...">{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Image d'illustration du Pack (Optionnel)</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
            <p style="font-size:0.75rem;color:var(--text-3);margin-top:0.25rem;">
                Si vous ne téléversez pas d'image, le pack affichera par défaut l'illustration de sa première formation.
            </p>
        </div>

        {{-- ─── Hero Slider ────────────────────────────── --}}
        <div style="background:rgba(99,102,241,0.07);border:1px solid rgba(99,102,241,0.2);border-radius:12px;padding:1.25rem;margin-bottom:1.5rem;">
            <div style="display:flex;align-items:center;gap:0.6rem;margin-bottom:1rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6366F1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="7" width="20" height="15" rx="2"/><polyline points="17 2 12 7 7 2"/>
                </svg>
                <span style="font-weight:700;font-size:0.9rem;color:#818CF8;">Affichage dans le Hero Slider</span>
            </div>

            <div style="display:flex;align-items:center;gap:2rem;flex-wrap:wrap;">
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <label class="toggle-switch" for="is_featured" style="display:flex;align-items:center;gap:0.6rem;cursor:pointer;">
                        <input type="checkbox" id="is_featured" name="is_featured" value="1"
                               {{ old('is_featured') ? 'checked' : '' }}
                               style="width:38px;height:20px;appearance:none;background:var(--bg-hover);border:1px solid var(--border);border-radius:999px;cursor:pointer;position:relative;transition:background 200ms;flex-shrink:0;"
                               onchange="this.style.background = this.checked ? '#6366F1' : ''"
                               onclick="this.style.background = this.checked ? '#6366F1' : 'var(--bg-hover)'">
                        <span style="font-size:0.875rem;font-weight:600;color:var(--text-1);">Mettre en avant (slide hero)</span>
                    </label>
                </div>

                <div>
                    <label for="hero_order" class="form-label" style="margin-bottom:0.3rem;">
                        Ordre d'affichage
                        <span style="font-size:0.75rem;color:var(--text-3);font-weight:400;">(0 = premier)</span>
                    </label>
                    <input type="number" class="form-control" id="hero_order" name="hero_order"
                           value="{{ old('hero_order', 0) }}" min="0" style="width:130px;">
                </div>
            </div>

            <p style="font-size:0.78rem;color:var(--text-3);margin-top:0.9rem;margin-bottom:0;">
                Les packs activés ici apparaîtront dans le carrousel hero de la page d'accueil, aux côtés des formations vedettes.
            </p>
        </div>

        {{-- ─── Formations incluses ─────────────────────── --}}
        <div class="mb-3">
            <label class="form-label" style="font-weight:700;">Formations incluses (2 minimum) *</label>
            <div style="background:var(--bg-surface);border:1px solid var(--border);border-radius:10px;max-height:240px;overflow-y:auto;padding:0.75rem 1rem;">
                @foreach($trainings as $training)
                    <label for="training_{{ $training->id }}"
                           style="display:flex;align-items:center;gap:0.75rem;padding:0.5rem 0;border-bottom:1px solid var(--border);cursor:pointer;">
                        <input class="form-check-input" type="checkbox"
                               name="trainings[]" id="training_{{ $training->id }}"
                               value="{{ $training->id }}"
                               {{ is_array(old('trainings')) && in_array($training->id, old('trainings')) ? 'checked' : '' }}>
                        <span>
                            <strong style="color:var(--text-1);font-size:0.875rem;">{{ $training->title }}</strong>
                            <span style="color:var(--text-3);font-size:0.78rem;margin-left:0.5rem;">{{ number_format($training->price, 0, ',', ' ') }} CFA</span>
                        </span>
                    </label>
                @endforeach
            </div>
        </div>

        <div style="display:flex;gap:0.75rem;margin-top:1.5rem;">
            <button type="submit" class="btn btn-primary" {{ $trainings->count() < 2 ? 'disabled' : '' }}>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Créer le Pack
            </button>
            <a href="{{ route('admin.bundles') }}" class="btn btn-outline">Annuler</a>
        </div>
    </form>
</div>

<script>
// Sync checkbox visual state on load
document.querySelectorAll('input[type=checkbox][id=is_featured]').forEach(cb => {
    cb.style.background = cb.checked ? '#6366F1' : '';
});
</script>
@endsection
