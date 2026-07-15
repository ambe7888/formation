@extends('admin.layout')

@section('title', 'Ajouter une formation')

@section('content')
    <div class="card card-borderless p-4">
        @if($categories->isEmpty())
            <div class="alert alert-warning">
                Aucune catégorie n'est définie. <a href="{{ route('admin.categories.create') }}">Ajoutez une catégorie</a> avant de créer une formation.
            </div>
        @endif

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

        <form action="{{ route('admin.trainings.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="title" class="form-label">Titre</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                </div>
                <div class="col-md-6">
                    <label for="category_id" class="form-label">Catégorie</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">Choisir une catégorie</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                </div>
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Date de début</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                </div>
                <div class="col-md-3">
                    <label for="planned_month" class="form-label">Mois prévu</label>
                    <select class="form-select" id="planned_month" name="planned_month">
                        <option value="">Sélectionner un mois</option>
                        <option value="Juin" {{ old('planned_month') == 'Juin' ? 'selected' : '' }}>Juin</option>
                        <option value="Juillet" {{ old('planned_month') == 'Juillet' ? 'selected' : '' }}>Juillet</option>
                        <option value="Août" {{ old('planned_month') == 'Août' ? 'selected' : '' }}>Août</option>
                        <option value="Septembre" {{ old('planned_month') == 'Septembre' ? 'selected' : '' }}>Septembre</option>
                        <option value="Octobre" {{ old('planned_month') == 'Octobre' ? 'selected' : '' }}>Octobre</option>
                        <option value="Novembre" {{ old('planned_month') == 'Novembre' ? 'selected' : '' }}>Novembre</option>
                        <option value="Décembre" {{ old('planned_month') == 'Décembre' ? 'selected' : '' }}>Décembre</option>
                        <option value="Janvier" {{ old('planned_month') == 'Janvier' ? 'selected' : '' }}>Janvier</option>
                        <option value="Février" {{ old('planned_month') == 'Février' ? 'selected' : '' }}>Février</option>
                        <option value="Mars" {{ old('planned_month') == 'Mars' ? 'selected' : '' }}>Mars</option>
                        <option value="Avril" {{ old('planned_month') == 'Avril' ? 'selected' : '' }}>Avril</option>
                        <option value="Mai" {{ old('planned_month') == 'Mai' ? 'selected' : '' }}>Mai</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="location" class="form-label">Lieu</label>
                    <input type="text" class="form-control" id="location" name="location" value="{{ old('location') }}">
                </div>
                <div class="col-md-3">
                    <label for="seats" class="form-label">Nombre de places</label>
                    <input type="number" class="form-control" id="seats" name="seats" value="{{ old('seats') }}" required>
                </div>
                <div class="col-md-6">
                    <label for="price" class="form-label">Prix</label>
                    <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}" required>
                </div>
                <div class="col-md-6">
                    <label for="promo_price" class="form-label">Prix promo</label>
                    <input type="number" class="form-control" id="promo_price" name="promo_price" value="{{ old('promo_price') }}">
                </div>
                <div class="col-12">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>
                <div class="col-12">
                    <label class="form-label d-block mb-2">Compétences acquises</label>
                    <div class="d-flex flex-wrap gap-3 p-3 border rounded" style="background: #fafafa; max-height: 150px; overflow-y: auto;">
                        @forelse($skills as $skill)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="skills[]" id="skill_{{ $skill->id }}" value="{{ $skill->id }}" {{ is_array(old('skills')) && in_array($skill->id, old('skills')) ? 'checked' : '' }}>
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
                    <h5 class="mb-3">Ressources (fichiers, vidéos, liens)</h5>
                    <div id="resources-container">
                        <div class="resource-row row g-2 mb-2 align-items-end">
                            <div class="col-md-4">
                                <label class="small text-muted mb-1">Titre de la ressource</label>
                                <input type="text" name="resource_title[0]" class="form-control form-control-sm" placeholder="ex: Manuel PDF Module 1">
                            </div>
                            <div class="col-md-3">
                                <label class="small text-muted mb-1">Type</label>
                                <select name="resource_type[0]" class="form-select form-select-sm" onchange="toggleResourceInput(this, 0)">
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
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addResourceRow()">➕ Ajouter une ressource</button>
                </div>

                <script>
                    let resourceIndex = 1;
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

                    function toggleResourceInput(select, index) {
                        const urlInput = document.getElementById('resource_url_' + index);
                        const mediaBtn = document.getElementById('resource_btn_' + index);
                        
                        if (select.value === 'link') {
                            urlInput.classList.remove('d-none');
                            urlInput.placeholder = "ex: https://...";
                            urlInput.readOnly = false;
                            mediaBtn.classList.add('d-none');
                        } else {
                            urlInput.classList.remove('d-none');
                            urlInput.placeholder = "Sélectionné depuis la médiathèque...";
                            urlInput.readOnly = true;
                            mediaBtn.classList.remove('d-none');
                        }
                    }

                    function openMediaModal(index) {
                        currentResourceTarget = index;
                        document.getElementById('mediaLibraryModal').classList.add('active');
                    }

                    function closeMediaPickerModal(e) {
                        if (!e || e.target === document.getElementById('mediaLibraryModal') || e.target.classList.contains('modal-close') || e.target.closest('.btn-secondary')) {
                            document.getElementById('mediaLibraryModal').classList.remove('active');
                        }
                    }

                    function selectMedia(filePath) {
                        if (currentResourceTarget !== null) {
                            const urlInput = document.getElementById('resource_url_' + currentResourceTarget);
                            urlInput.value = filePath;
                            document.getElementById('mediaLibraryModal').classList.remove('active');
                        }
                    }

                    function filterPickerMedia() {
                        const q = document.getElementById('pickerSearchInput').value.toLowerCase();
                        const items = document.querySelectorAll('.picker-media-item');
                        items.forEach(item => {
                            const name = item.getAttribute('data-name');
                            if (name.includes(q)) {
                                item.style.display = '';
                            } else {
                                item.style.display = 'none';
                            }
                        });
                    }
                </script>

                <div class="col-md-4">
                    <label for="hero_order" class="form-label">Position du slide</label>
                    <input type="number" class="form-control" id="hero_order" name="hero_order" value="{{ old('hero_order') }}" min="0">
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Publier sur la page d'accueil</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">Afficher dans le slider hero</label>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Créer</button>
                <a href="{{ route('admin.trainings') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>

<!-- Media Library Modal (Native Overlay) -->
<div id="mediaLibraryModal" class="modal-overlay" onclick="closeMediaPickerModal(event)">
    <div class="modal-container" style="max-width: 720px;" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h5 class="modal-title">Sélectionner un fichier de la médiathèque</h5>
            <button type="button" class="modal-close" onclick="closeMediaPickerModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
                <input type="text" id="pickerSearchInput" class="form-control" placeholder="Rechercher un fichier par nom..." onkeyup="filterPickerMedia()" style="max-width: 320px;">
                <a href="{{ route('admin.media.index') }}" target="_blank" class="btn btn-sm btn-outline-primary">+ Uploader dans la médiathèque</a>
            </div>

            @if(isset($media) && $media->isNotEmpty())
                <div class="row g-2" style="max-height: 50vh; overflow-y: auto;">
                    @foreach($media as $item)
                        <div class="col-md-4 picker-media-item" data-name="{{ strtolower($item->name) }}">
                            <div class="p-3 border rounded text-center h-100 cursor-pointer media-picker-card" onclick="selectMedia('{{ $item->file_path }}')" style="background: var(--bg-surface); cursor: pointer; transition: all 0.2s;">
                                <div class="mb-1">
                                    @if($item->type == 'image')
                                        <span class="badge badge-primary">Image</span>
                                    @elseif($item->type == 'video')
                                        <span class="badge badge-danger">Vidéo</span>
                                    @else
                                        <span class="badge badge-muted">Document</span>
                                    @endif
                                </div>
                                <div class="fw-semibold small text-truncate" title="{{ $item->name }}" style="color: var(--text-1);">{{ $item->name }}</div>
                                <div class="text-muted" style="font-size: 0.7rem;">{{ number_format($item->size / 1048576, 2) }} Mo</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    <p class="mb-2">La médiathèque est vide.</p>
                    <a href="{{ route('admin.media.index') }}" target="_blank" class="btn btn-sm btn-primary">Ajouter un fichier à la médiathèque</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection