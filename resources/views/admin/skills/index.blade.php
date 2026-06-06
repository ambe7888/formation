@extends('admin.layout')

@section('title', 'Compétences')

@section('content')
<div style="display:grid;grid-template-columns:1fr 340px;gap:1.5rem;align-items:start;">

    {{-- ─── Liste ─────────────────────────────────────── --}}
    <div class="card p-4">
        <h5 style="margin-bottom:1.25rem;">Liste des compétences</h5>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Slug</th>
                        <th>Aperçu Badge</th>
                        <th>Formations</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($skills as $skill)
                        <tr>
                            <td><strong>{{ $skill->name }}</strong></td>
                            <td><code style="font-family:'Fira Code',monospace;font-size:0.8rem;color:var(--text-3);">{{ $skill->slug }}</code></td>
                            <td>
                                <span style="display:inline-flex;align-items:center;padding:0.25rem 0.7rem;border-radius:999px;font-size:0.78rem;font-weight:600;color:#fff;background-color:{{ $skill->badge_color }};">
                                    {{ $skill->name }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-muted">{{ $skill->trainings_count }} formation(s)</span>
                            </td>
                            <td style="text-align:right;white-space:nowrap;" class="table-actions">
                                <a href="{{ route('admin.skills.edit', $skill) }}" class="btn btn-sm btn-outline">Modifier</a>
                                <form action="{{ route('admin.skills.destroy', $skill) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cette compétence ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;color:var(--text-3);padding:3rem 1rem;">
                                Aucune compétence enregistrée.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ─── Formulaire ajout ──────────────────────────── --}}
    <div class="card p-4">
        <h5 style="margin-bottom:1.25rem;">Ajouter une compétence</h5>
        <form action="{{ route('admin.skills.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Titre *</label>
                <input type="text" class="form-control" id="name" name="name" required placeholder="ex : Prompt Engineering">
            </div>
            <div class="mb-3">
                <label for="slug" class="form-label">Slug <span style="font-weight:400;color:var(--text-3);">(optionnel)</span></label>
                <input type="text" class="form-control" id="slug" name="slug" placeholder="ex : prompt-engineering">
                <div style="font-size:0.75rem;color:var(--text-3);margin-top:0.3rem;">Laissez vide pour générer automatiquement.</div>
            </div>
            <div class="mb-3">
                <label for="badge_color" class="form-label">Couleur du badge *</label>
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <input type="color" id="badge_color" name="badge_color" value="#4f46e5"
                           style="width:44px;height:36px;border-radius:8px;border:1px solid var(--border);background:var(--bg-surface);cursor:pointer;padding:2px;">
                    <span style="font-size:0.82rem;color:var(--text-3);">Cliquez pour choisir</span>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100" style="margin-top:0.5rem;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Créer la compétence
            </button>
        </form>
    </div>
</div>
@endsection
