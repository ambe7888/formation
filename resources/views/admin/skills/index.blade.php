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
                                <button onclick="openEditModal(this)" 
                                        data-id="{{ $skill->id }}"
                                        data-name="{{ $skill->name }}"
                                        data-slug="{{ $skill->slug }}"
                                        data-color="{{ $skill->badge_color }}"
                                        class="btn btn-sm btn-outline">Modifier</button>
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
                <input type="text" class="form-control @error('name') @if(!session('open_edit_modal')) is-invalid @endif @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="ex : Prompt Engineering">
                @error('name')
                    @if(!session('open_edit_modal'))
                        <div class="invalid-feedback text-danger text-sm mt-1">{{ $message }}</div>
                    @endif
                @enderror
            </div>
            <div class="mb-3">
                <label for="slug" class="form-label">Slug <span style="font-weight:400;color:var(--text-3);">(optionnel)</span></label>
                <input type="text" class="form-control @error('slug') @if(!session('open_edit_modal')) is-invalid @endif @enderror" id="slug" name="slug" value="{{ old('slug') }}" placeholder="ex : prompt-engineering">
                <div style="font-size:0.75rem;color:var(--text-3);margin-top:0.3rem;">Laissez vide pour générer automatiquement.</div>
                @error('slug')
                    @if(!session('open_edit_modal'))
                        <div class="invalid-feedback text-danger text-sm mt-1">{{ $message }}</div>
                    @endif
                @enderror
            </div>
            <div class="mb-3">
                <label for="badge_color" class="form-label">Couleur du badge *</label>
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <input type="color" id="badge_color" name="badge_color" value="{{ old('badge_color', '#4f46e5') }}"
                           style="width:44px;height:36px;border-radius:8px;border:1px solid var(--border);background:var(--bg-surface);cursor:pointer;padding:2px;">
                    <span style="font-size:0.82rem;color:var(--text-3);">Cliquez pour choisir</span>
                </div>
                @error('badge_color')
                    @if(!session('open_edit_modal'))
                        <div class="invalid-feedback text-danger text-sm mt-1">{{ $message }}</div>
                    @endif
                @enderror
            </div>
            <button type="submit" class="btn btn-primary w-100" style="margin-top:0.5rem;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Créer la compétence
            </button>
        </form>
    </div>
</div>

<!-- ── MODAL: EDIT SKILL ── -->
<div class="modal-overlay" id="editSkillModal" onclick="closeEditModal(event)">
    <div class="modal-container" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 class="modal-title">Modifier la compétence</h3>
            <button class="modal-close" onclick="closeEditModal()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <div class="modal-body">
            <form id="edit_skill_form" action="" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="edit_name" class="form-label">Titre *</label>
                    <input type="text" class="form-control @error('name') @if(session('open_edit_modal')) is-invalid @endif @enderror" id="edit_name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        @if(session('open_edit_modal'))
                            <div class="invalid-feedback text-danger text-sm mt-1">{{ $message }}</div>
                        @endif
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="edit_slug" class="form-label">Slug <span style="font-weight:400;color:var(--text-3);">(optionnel)</span></label>
                    <input type="text" class="form-control @error('slug') @if(session('open_edit_modal')) is-invalid @endif @enderror" id="edit_slug" name="slug" value="{{ old('slug') }}">
                    <div style="font-size:0.75rem;color:var(--text-3);margin-top:0.3rem;">Laissez vide pour générer automatiquement.</div>
                    @error('slug')
                        @if(session('open_edit_modal'))
                            <div class="invalid-feedback text-danger text-sm mt-1">{{ $message }}</div>
                        @endif
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="edit_badge_color" class="form-label">Couleur du badge *</label>
                    <div style="display:flex;align-items:center;gap:0.75rem;">
                        <input type="color" id="edit_badge_color" name="badge_color" value="{{ old('badge_color') }}"
                               style="width:44px;height:36px;border-radius:8px;border:1px solid var(--border);background:var(--bg-surface);cursor:pointer;padding:2px;">
                        <span style="font-size:0.82rem;color:var(--text-3);">Cliquez pour choisir</span>
                    </div>
                    @error('badge_color')
                        @if(session('open_edit_modal'))
                            <div class="invalid-feedback text-danger text-sm mt-1">{{ $message }}</div>
                        @endif
                    @enderror
                </div>

                <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                    <button type="button" class="btn btn-secondary me-2" onclick="closeEditModal(event)">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const editModal = document.getElementById('editSkillModal');

    function openEditModal(btn) {
        const id = btn.getAttribute('data-id');
        const name = btn.getAttribute('data-name');
        const slug = btn.getAttribute('data-slug');
        const color = btn.getAttribute('data-color');

        // Set action url
        document.getElementById('edit_skill_form').action = `{{ url('/admin/skills') }}/${id}`;
        
        // Fill form fields
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_slug').value = slug === 'null' ? '' : (slug || '');
        document.getElementById('edit_badge_color').value = color;

        editModal.classList.add('active');
    }

    function closeEditModal(e) {
        if (e && e.target !== editModal && !e.target.closest('.modal-close') && !e.target.closest('.btn-secondary')) return;
        editModal.classList.remove('active');
    }

    @if(session('open_edit_modal'))
        document.addEventListener('DOMContentLoaded', function() {
            const skillId = "{{ session('open_edit_modal') }}";
            const btn = document.querySelector(`button[data-id="${skillId}"]`);
            if (btn) {
                openEditModal(btn);
                // Override with user's old input values
                document.getElementById('edit_name').value = "{{ old('name') }}";
                document.getElementById('edit_slug').value = "{{ old('slug') }}";
                document.getElementById('edit_badge_color').value = "{{ old('badge_color') }}";
            }
        });
    @endif
</script>
@endsection
