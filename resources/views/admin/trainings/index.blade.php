@extends('admin.layout')

@section('title', 'Formations')

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h4 class="mb-0 fw-bold" style="color: var(--text-1);">Liste des Formations</h4>
        <a href="{{ route('admin.trainings.create') }}" class="btn btn-success d-inline-flex align-items-center gap-1">
            <span>+</span> <span>Ajouter une formation</span>
        </a>
    </div>

    <div class="card card-borderless p-4">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Catégorie</th>
                    <th>Date</th>
                    <th>Prix</th>
                    <th>Places</th>
                    <th>Slider</th>
                    <th>Statut</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($trainings as $training)
                    @php
                        $trainingData = [
                            'id' => $training->id,
                            'title' => $training->title,
                            'category' => $training->category?->name ?? $training->category,
                            'description' => $training->description,
                            'start_date' => $training->start_date ? $training->start_date->format('d/m/Y') : 'N/A',
                            'planned_month' => $training->planned_month ?? 'N/A',
                            'location' => $training->location ?? 'Non précisé',
                            'price' => number_format($training->price, 0, ',', ' ') . ' XOF',
                            'promo_price' => $training->promo_price ? number_format($training->promo_price, 0, ',', ' ') . ' XOF' : null,
                            'seats' => $training->seats,
                            'image' => $training->image_url ? asset($training->image_url) : null,
                            'is_active' => $training->is_active,
                            'is_featured' => $training->is_featured,
                            'hero_order' => $training->hero_order,
                            'edit_url' => route('admin.trainings.edit', $training),
                            'skills' => $training->skills->map(fn($s) => ['name' => $s->name, 'color' => $s->badge_color]),
                            'resources' => $training->resources->map(fn($r) => [
                                'title' => $r->title,
                                'type' => $r->type,
                                'url' => str_starts_with($r->url, 'http') ? $r->url : asset('storage/' . $r->url),
                                'description' => $r->description
                            ])
                        ];
                    @endphp
                    <tr>
                        <td><strong>{{ $training->title }}</strong></td>
                        <td><span class="badge badge-primary">{{ $training->category?->name ?? $training->category }}</span></td>
                        <td>{{ $training->start_date ? $training->start_date->format('d/m/Y') : '' }}</td>
                        <td>{{ number_format($training->price, 0, ',', ' ') }} XOF</td>
                        <td>{{ $training->seats }}</td>
                        <td>
                            @if($training->is_featured)
                                <span class="badge bg-info text-dark">Hero</span>
                            @endif
                        </td>
                        <td>
                            <span class="status-badge {{ $training->is_active ? 'status-active' : 'status-inactive' }}">
                                {{ $training->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="text-end table-actions">
                            <button type="button" class="btn btn-sm btn-outline-secondary me-1" onclick="openQuickView({{ json_encode($trainingData) }})">
                                👁️ Aperçu
                            </button>
                            <a href="{{ route('admin.trainings.edit', $training) }}" class="btn btn-sm btn-primary me-1">Modifier</a>
                            <form action="{{ route('admin.trainings.destroy', $training) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette formation ?');">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

<!-- Quick View Modal (Native Overlay) -->
<div id="quickViewModal" class="modal-overlay" onclick="closeQuickViewModal(event)">
    <div onclick="event.stopPropagation()" style="
        background: var(--bg-card);
        border-radius: 12px;
        width: 100%;
        max-width: 720px;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        overflow: hidden;
    ">
        <!-- Header -->
        <div style="padding: 18px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 1.2rem;">📖</span>
                <h5 style="margin: 0; font-weight: 700; color: var(--text-1);" id="modalTrainingTitle">Aperçu de la formation</h5>
            </div>
            <button onclick="closeQuickViewModal()" style="background: none; border: none; font-size: 1.4rem; color: var(--text-2); cursor: pointer; line-height: 1;">&times;</button>
        </div>

        <!-- Body -->
        <div style="padding: 24px; overflow-y: auto; flex: 1;" id="modalBodyContent">
            <!-- Dynamic Content -->
        </div>

        <!-- Footer -->
        <div style="padding: 14px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px;">
            <button onclick="closeQuickViewModal()" class="btn btn-secondary">Fermer</button>
            <a href="#" id="modalEditBtn" class="btn btn-primary">Modifier cette formation</a>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openQuickView(data) {
    document.getElementById('modalTrainingTitle').innerText = data.title;
    document.getElementById('modalEditBtn').href = data.edit_url;

    let imageHtml = data.image ? `<img src="${data.image}" style="width: 100%; max-height: 180px; object-fit: cover; border-radius: 8px; margin-bottom: 16px;">` : '';
    
    let skillsHtml = '';
    if (data.skills && data.skills.length > 0) {
        skillsHtml = '<div style="margin-top: 16px;"><strong style="font-size: 0.9rem; color: var(--text-1);">Compétences acquises :</strong><div style="display: flex; flex-wrap: wrap; gap: 6px; margin-top: 8px;">';
        data.skills.forEach(s => {
            skillsHtml += `<span style="background-color: ${s.color}; color: #fff; padding: 3px 10px; border-radius: 20px; font-size: 0.78rem; font-weight: 600;">${s.name}</span>`;
        });
        skillsHtml += '</div></div>';
    }

    let resourcesHtml = '';
    if (data.resources && data.resources.length > 0) {
        resourcesHtml = '<div style="margin-top: 20px;"><strong style="font-size: 0.95rem; color: var(--text-1);">📚 Supports & Ressources d\'apprentissage (' + data.resources.length + ') :</strong><ul style="list-style: none; padding: 0; margin-top: 10px;">';
        data.resources.forEach(r => {
            let icon = '🔗';
            let action = 'Ouvrir';
            if (r.type === 'file') { icon = '📄'; action = 'Télécharger'; }
            else if (r.type === 'video') { icon = '🎥'; action = 'Regarder'; }

            resourcesHtml += `
                <li style="display: flex; justify-content: space-between; align-items: center; padding: 10px 14px; background: var(--bg-surface); border: 1px solid var(--border); border-radius: 8px; margin-bottom: 6px;">
                    <div>
                        <div style="font-weight: 600; font-size: 0.88rem; color: var(--text-1);">${icon} ${r.title}</div>
                        ${r.description ? `<div style="font-size: 0.75rem; color: var(--text-3); margin-top: 2px;">${r.description}</div>` : ''}
                    </div>
                    <a href="${r.url}" target="_blank" class="btn btn-sm btn-outline-primary" style="font-size: 0.75rem; text-decoration: none;">${action}</a>
                </li>`;
        });
        resourcesHtml += '</ul></div>';
    } else {
        resourcesHtml = '<div style="margin-top: 20px; color: var(--text-3); font-size: 0.85rem; font-style: italic;">Aucune ressource rattachée pour le moment.</div>';
    }

    let html = `
        ${imageHtml}
        
        <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 16px; flex-wrap: wrap;">
            <span class="badge badge-primary">${data.category}</span>
            <span class="status-badge ${data.is_active ? 'status-active' : 'status-inactive'}">${data.is_active ? 'Active' : 'Inactive'}</span>
            ${data.is_featured ? '<span class="badge bg-info text-dark">Hero Slider (Ordre: ' + data.hero_order + ')</span>' : ''}
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 12px; margin-bottom: 16px; background: var(--bg-surface); padding: 14px; border-radius: 8px; border: 1px solid var(--border);">
            <div>
                <small style="color: var(--text-3); display: block;">Prix public</small>
                <strong style="color: var(--text-1); font-size: 0.95rem;">${data.price}</strong>
            </div>
            ${data.promo_price ? `
            <div>
                <small style="color: var(--text-3); display: block;">Prix promo</small>
                <strong style="color: var(--success); font-size: 0.95rem;">${data.promo_price}</strong>
            </div>` : ''}
            <div>
                <small style="color: var(--text-3); display: block;">Date de début</small>
                <strong style="color: var(--text-1); font-size: 0.9rem;">${data.start_date}</strong>
            </div>
            <div>
                <small style="color: var(--text-3); display: block;">Mois prévu</small>
                <strong style="color: var(--text-1); font-size: 0.9rem;">${data.planned_month}</strong>
            </div>
            <div>
                <small style="color: var(--text-3); display: block;">Places</small>
                <strong style="color: var(--text-1); font-size: 0.9rem;">${data.seats} places</strong>
            </div>
            <div>
                <small style="color: var(--text-3); display: block;">Lieu</small>
                <strong style="color: var(--text-1); font-size: 0.9rem;">${data.location}</strong>
            </div>
        </div>

        <div style="margin-bottom: 16px;">
            <strong style="display: block; margin-bottom: 4px; color: var(--text-1);">Description :</strong>
            <p style="color: var(--text-2); font-size: 0.9rem; line-height: 1.5; white-space: pre-line; margin: 0;">${data.description}</p>
        </div>

        ${skillsHtml}
        ${resourcesHtml}
    `;

    document.getElementById('modalBodyContent').innerHTML = html;
    document.getElementById('quickViewModal').classList.add('active');
}

function closeQuickViewModal(e) {
    if (!e || e.target === document.getElementById('quickViewModal') || e.target.classList.contains('modal-close') || e.target.closest('.btn-secondary')) {
        document.getElementById('quickViewModal').classList.remove('active');
    }
}
</script>
@endpush
@endsection