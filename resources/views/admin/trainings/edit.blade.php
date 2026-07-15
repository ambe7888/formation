@extends('admin.layout')

@section('title', 'Modifier une formation')

@section('content')
    <div class="card card-borderless p-4">
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Oups! Il y a des erreurs dans votre formulaire :</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('admin.trainings.update', $training) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Titre</label>
                    <input type="text" name="title" value="{{ old('title', $training->title) }}" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Catégorie</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">Choisir une catégorie</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $training->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4" required>{{ old('description', $training->description) }}</textarea>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date de début</label>
                    <input type="date" name="start_date" value="{{ old('start_date', $training->start_date->format('Y-m-d')) }}" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Mois prévu</label>
                    <select name="planned_month" class="form-select">
                        <option value="">Sélectionner un mois</option>
                        <option value="Juin" {{ old('planned_month', $training->planned_month) == 'Juin' ? 'selected' : '' }}>Juin</option>
                        <option value="Juillet" {{ old('planned_month', $training->planned_month) == 'Juillet' ? 'selected' : '' }}>Juillet</option>
                        <option value="Août" {{ old('planned_month', $training->planned_month) == 'Août' ? 'selected' : '' }}>Août</option>
                        <option value="Septembre" {{ old('planned_month', $training->planned_month) == 'Septembre' ? 'selected' : '' }}>Septembre</option>
                        <option value="Octobre" {{ old('planned_month', $training->planned_month) == 'Octobre' ? 'selected' : '' }}>Octobre</option>
                        <option value="Novembre" {{ old('planned_month', $training->planned_month) == 'Novembre' ? 'selected' : '' }}>Novembre</option>
                        <option value="Décembre" {{ old('planned_month', $training->planned_month) == 'Décembre' ? 'selected' : '' }}>Décembre</option>
                        <option value="Janvier" {{ old('planned_month', $training->planned_month) == 'Janvier' ? 'selected' : '' }}>Janvier</option>
                        <option value="Février" {{ old('planned_month', $training->planned_month) == 'Février' ? 'selected' : '' }}>Février</option>
                        <option value="Mars" {{ old('planned_month', $training->planned_month) == 'Mars' ? 'selected' : '' }}>Mars</option>
                        <option value="Avril" {{ old('planned_month', $training->planned_month) == 'Avril' ? 'selected' : '' }}>Avril</option>
                        <option value="Mai" {{ old('planned_month', $training->planned_month) == 'Mai' ? 'selected' : '' }}>Mai</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Lieu</label>
                    <input type="text" name="location" value="{{ old('location', $training->location) }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Places</label>
                    <input type="number" name="seats" value="{{ old('seats', $training->seats) }}" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Prix</label>
                    <input type="number" name="price" value="{{ old('price', $training->price) }}" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Prix promo</label>
                    <input type="number" name="promo_price" value="{{ old('promo_price', $training->promo_price) }}" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label">Image</label>
                    <input type="file" name="image" class="form-control">
                    @if($training->image_url)
                        <small class="text-muted">Image actuelle : {{ $training->image_url }}</small>
                    @endif
                </div>
                <div class="col-12">
                    <label class="form-label d-block mb-2">Compétences acquises</label>
                    <div class="d-flex flex-wrap gap-3 p-3 border rounded" style="background: #fafafa; max-height: 150px; overflow-y: auto;">
                        @forelse($skills as $skill)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="skills[]" id="skill_{{ $skill->id }}" value="{{ $skill->id }}" {{ is_array(old('skills')) ? (in_array($skill->id, old('skills')) ? 'checked' : '') : ($training->skills->contains($skill->id) ? 'checked' : '') }}>
                                <label class="form-check-label" for="skill_{{ $skill->id }}">
                                    <span class="badge" style="background-color: {{ $skill->badge_color }}; color: #fff;">{{ $skill->name }}</span>
                                </label>
                            </div>
                        @empty
                            <span class="text-muted small">Aucune compétence enregistrée. <a href="{{ route('admin.skills') }}" target="_blank">Gérer les compétences</a></span>
                        @endforelse
                    </div>
                </div>
                
                <!-- Dynamic Resources Section -->
                <div class="col-12 mt-4">
                    <label class="form-label d-block mb-2"><strong>Supports de cours & Ressources d'apprentissage (débloqués après paiement)</strong></label>
                    <div id="resources-container" class="p-3 border rounded" style="background: #f8fafc;">
                        @forelse($training->resources as $resource)
                            @php $index = $loop->index; @endphp
                            <div class="resource-row row g-2 mb-2 align-items-end" data-index="{{ $index }}">
                                <div class="col-md-4">
                                    <label class="small text-muted mb-1">Titre de la ressource</label>
                                    <input type="text" name="resource_title[{{ $index }}]" value="{{ $resource->title }}" class="form-control form-control-sm" placeholder="ex: Manuel PDF Module 1">
                                </div>
                                <div class="col-md-3">
                                    <label class="small text-muted mb-1">Type</label>
                                    <select name="resource_type[{{ $index }}]" class="form-select form-select-sm" onchange="toggleResourceInput(this)">
                                        <option value="link" {{ $resource->type === 'link' ? 'selected' : '' }}>Lien externe</option>
                                        <option value="file" {{ $resource->type === 'file' ? 'selected' : '' }}>Fichier / Document</option>
                                        <option value="video" {{ $resource->type === 'video' ? 'selected' : '' }}>Fichier Vidéo</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="small text-muted mb-1">Source</label>
                                    <input type="text" id="resource_url_{{ $index }}" name="resource_url[{{ $index }}]" value="{{ $resource->url }}" class="form-control form-control-sm {{ $resource->type !== 'link' ? 'd-none' : '' }}" placeholder="ex: https://..." {{ $resource->type !== 'link' ? 'readonly' : '' }}>
                                    <button type="button" id="resource_btn_{{ $index }}" class="btn btn-sm btn-outline-primary w-100 {{ $resource->type === 'link' ? 'd-none' : '' }}" onclick="openMediaModal({{ $index }})">Sélectionner dans la Médiathèque</button>
                                    @if($resource->type !== 'link')
                                        <div class="small mt-1 text-truncate text-muted file-indicator">
                                            @php
                                                $fileUrl = str_starts_with($resource->url, 'http') ? $resource->url : Storage::url($resource->url);
                                            @endphp
                                            @if($resource->type === 'video')
                                                🎥 Actuel: <a href="{{ $fileUrl }}" target="_blank">Voir la vidéo</a>
                                            @else
                                                📄 Actuel: <a href="{{ $fileUrl }}" target="_blank">Voir le fichier</a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="removeResourceRow(this)">Supprimer</button>
                                </div>
                            </div>
                        @empty
                            <div class="resource-row row g-2 mb-2 align-items-end" data-index="0">
                                <div class="col-md-4">
                                    <label class="small text-muted mb-1">Titre de la ressource</label>
                                    <input type="text" name="resource_title[0]" class="form-control form-control-sm" placeholder="ex: Manuel PDF Module 1">
                                </div>
                                <div class="col-md-3">
                                    <label class="small text-muted mb-1">Type</label>
                                    <select name="resource_type[0]" class="form-select form-select-sm" onchange="toggleResourceInput(this)">
                                        <option value="link">Lien externe</option>
                                        <option value="file">Fichier / Document</option>
                                        <option value="video">Fichier Vidéo</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="small text-muted mb-1">Source</label>
                                    <input type="text" id="resource_url_0" name="resource_url[0]" class="form-control form-control-sm" placeholder="ex: https://...">
                                    <button type="button" id="resource_btn_0" class="btn btn-sm btn-outline-primary w-100 d-none" onclick="openMediaModal(0)">Sélectionner dans la Médiathèque</button>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="removeResourceRow(this)">Supprimer</button>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addResourceRow()">➕ Ajouter une ressource</button>
                </div>

                <script>
                    let resourceIndex = {{ $training->resources->count() > 0 ? $training->resources->count() : 1 }};
                    let currentResourceTarget = null;

                    function addResourceRow() {
                        const container = document.getElementById('resources-container');
                        const row = document.createElement('div');
                        row.className = 'resource-row row g-2 mb-2 align-items-end';
                        row.innerHTML = `
                            <div class="col-md-4">
                                <input type="text" name="resource_title[${resourceIndex}]" class="form-control form-control-sm" placeholder="ex: Manuel PDF Module 1">
                            </div>
                            <div class="col-md-3">
                                <select name="resource_type[${resourceIndex}]" class="form-select form-select-sm" onchange="toggleResourceInput(this, ${resourceIndex})">
                                    <option value="link">Lien externe</option>
                                    <option value="file">Fichier / Document</option>
                                    <option value="video">Fichier Vidéo</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="text" id="resource_url_${resourceIndex}" name="resource_url[${resourceIndex}]" class="form-control form-control-sm" placeholder="ex: https://...">
                                <button type="button" id="resource_btn_${resourceIndex}" class="btn btn-sm btn-outline-primary w-100 d-none" onclick="openMediaModal(${resourceIndex})">Sélectionner dans la Médiathèque</button>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="removeResourceRow(this)">Supprimer</button>
                            </div>
                        `;
                        container.appendChild(row);
                        resourceIndex++;
                    }

                    function removeResourceRow(button) {
                        const row = button.closest('.resource-row');
                        if (row) {
                            row.remove();
                        }
                    }

                    function toggleResourceInput(select, index = null) {
                        const container = select.closest('.resource-row');
                        if (index === null) {
                            index = container.getAttribute('data-index');
                        }
                        
                        const urlInput = document.getElementById('resource_url_' + index);
                        const mediaBtn = document.getElementById('resource_btn_' + index);
                        const indicator = container.querySelector('.file-indicator');
                        
                        if (select.value === 'link') {
                            urlInput.classList.remove('d-none');
                            urlInput.readOnly = false;
                            urlInput.placeholder = "ex: https://...";
                            if (mediaBtn) mediaBtn.classList.add('d-none');
                            if (indicator) indicator.style.display = 'none';
                        } else {
                            urlInput.classList.remove('d-none');
                            urlInput.readOnly = true;
                            urlInput.placeholder = "Sélectionné depuis la médiathèque...";
                            if (mediaBtn) mediaBtn.classList.remove('d-none');
                            if (indicator) indicator.style.display = 'block';
                        }
                    }

                    function openMediaModal(index) {
                        currentResourceTarget = index;
                        const modal = new bootstrap.Modal(document.getElementById('mediaLibraryModal'));
                        modal.show();
                    }

                    function selectMedia(filePath) {
                        if (currentResourceTarget !== null) {
                            const urlInput = document.getElementById('resource_url_' + currentResourceTarget);
                            if (urlInput) {
                                urlInput.value = filePath;
                                // clear old indicator text visually for feedback
                                const container = urlInput.closest('.resource-row');
                                const indicator = container.querySelector('.file-indicator');
                                if (indicator) {
                                    indicator.innerHTML = '<span class="text-success">Fichier mis à jour depuis la médiathèque !</span>';
                                }
                            }
                            bootstrap.Modal.getInstance(document.getElementById('mediaLibraryModal')).hide();
                        }
                    }
                </script>

                <div class="col-md-4">
                    <label class="form-label">Position slider</label>
                    <input type="number" name="hero_order" value="{{ old('hero_order', $training->hero_order) }}" class="form-control" min="0">
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $training->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Publier sur la page d'accueil</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $training->is_featured) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">Afficher dans le slider hero</label>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                <a href="{{ route('admin.trainings') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
    </div>

<!-- Media Library Modal -->
<div class="modal fade" id="mediaLibraryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background: var(--bg-card); border: 1px solid var(--border);">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" style="color: var(--text-1);">Choisir depuis la médiathèque</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: var(--close-filter);"></button>
            </div>
            <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
                @if(isset($media) && $media->isNotEmpty())
                    <div class="row g-3">
                        @foreach($media as $item)
                            <div class="col-md-4">
                                <div class="card card-borderless text-center p-3 h-100 cursor-pointer" onclick="selectMedia('{{ $item->file_path }}')" style="cursor: pointer;">
                                    <div class="mb-2">
                                        @if($item->type == 'image')
                                            <span class="badge bg-primary bg-opacity-10 text-primary mb-2">Image</span>
                                        @elseif($item->type == 'video')
                                            <span class="badge bg-danger bg-opacity-10 text-danger mb-2">Vidéo</span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary mb-2">Document</span>
                                        @endif
                                    </div>
                                    <div class="fw-semibold small text-truncate" title="{{ $item->name }}">{{ $item->name }}</div>
                                    <div class="text-muted" style="font-size: 0.7rem;">{{ number_format($item->size / 1048576, 2) }} Mo</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <p>La médiathèque est vide.</p>
                        <a href="{{ route('admin.media.index') }}" target="_blank" class="btn btn-sm btn-primary mt-2">Aller à la médiathèque</a>
                    </div>
                @endif
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
            </div>
        </div>
    </div>
</div>
@endsection
