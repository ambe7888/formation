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
                                    <label class="small text-muted mb-1">Titre de la ressource <span class="text-danger">*</span></label>
                                    <input type="text" name="resource_title[{{ $index }}]" value="{{ $resource->title }}" class="form-control form-control-sm" placeholder="ex: Manuel PDF Module 1" required>
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
                                    <label class="small text-muted mb-1">Titre de la ressource <span class="text-danger">*</span></label>
                                    <input type="text" name="resource_title[0]" class="form-control form-control-sm" placeholder="ex: Manuel PDF Module 1" required>
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
                                <input type="text" name="resource_title[${resourceIndex}]" class="form-control form-control-sm" placeholder="ex: Manuel PDF Module 1" required>
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
                            if (urlInput) {
                                urlInput.value = filePath;
                                const container = urlInput.closest('.resource-row');
                                const indicator = container.querySelector('.file-indicator');
                                if (indicator) {
                                    indicator.innerHTML = '<span class="text-success fw-semibold">Fichier sélectionné depuis la médiathèque !</span>';
                                }
                            }
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

<!-- Media Library Modal — New Design -->
<div id="mediaLibraryModal" class="modal-overlay" onclick="closeMediaPickerModal(event)">
    <div onclick="event.stopPropagation()" style="
        background: var(--bg-card);
        border-radius: 12px;
        width: 100%;
        max-width: 780px;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        overflow: hidden;
    ">
        <!-- Header -->
        <div style="padding: 18px 24px 14px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 10px;">
            <svg width="18" height="18" fill="none" stroke="var(--primary)" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
            <span style="font-weight: 700; font-size: 1rem; color: var(--text-1);">Médiathèque</span>
            <span id="pickerFileCount" style="font-size: 0.8rem; background: var(--primary); color: #fff; padding: 1px 8px; border-radius: 20px; font-weight: 600;">{{ isset($media) ? $media->count() : 0 }}</span>
            <button onclick="closeMediaPickerModal()" style="margin-left: auto; background: none; border: none; font-size: 1.4rem; color: var(--text-2); cursor: pointer; line-height: 1;">&times;</button>
        </div>

        <!-- Toolbar: Search + Upload -->
        <div style="padding: 12px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 10px;">
            <div style="position: relative; flex: 1;">
                <svg width="15" height="15" fill="none" stroke="var(--text-3)" stroke-width="2" viewBox="0 0 24 24" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%);"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="text" id="pickerSearchInput" placeholder="Rechercher des fichiers..." onkeyup="filterPickerMedia()" style="width: 100%; padding: 8px 10px 8px 34px; border: 1px solid var(--border); border-radius: 8px; background: var(--bg-surface); color: var(--text-1); font-size: 0.875rem; outline: none;">
            </div>
            <button onclick="document.getElementById('pickerFileUploadInput').click()" style="display: flex; align-items: center; gap: 6px; padding: 8px 14px; background: var(--primary); color: #fff; border: none; border-radius: 8px; font-size: 0.85rem; font-weight: 600; cursor: pointer; white-space: nowrap;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Téléverser
            </button>
            <input type="file" id="pickerFileUploadInput" class="d-none" onchange="uploadFromPicker(this)">
        </div>

        <!-- Upload Progress Bar (hidden by default) -->
        <div id="pickerUploadProgress" style="display:none; padding: 8px 24px; background: var(--bg-surface); border-bottom: 1px solid var(--border); align-items: center; gap: 12px;">
            <div style="flex: 1; height: 6px; background: var(--border); border-radius: 10px; overflow: hidden;">
                <div id="pickerProgressBar" style="height: 100%; width: 0%; background: var(--primary); border-radius: 10px; transition: width 0.1s;"></div>
            </div>
            <span id="pickerProgressText" style="font-size: 0.78rem; color: var(--text-2); min-width: 36px; text-align: right;">0%</span>
        </div>

        <!-- File Count Info -->
        <div id="pickerFileInfo" style="padding: 6px 24px; font-size: 0.78rem; color: var(--text-3); border-bottom: 1px solid var(--border);">
            {{ isset($media) ? $media->count() : 0 }} fichier(s) · Page 1 de 1
        </div>

        <!-- Grid Body -->
        <div id="pickerGridBody" style="padding: 16px 24px; overflow-y: auto; flex: 1;">
            @if(isset($media) && $media->isNotEmpty())
                <div class="row g-2" id="pickerGrid">
                    @foreach($media as $item)
                        <div class="col-6 col-md-3 picker-media-item" data-name="{{ strtolower($item->name) }}">
                            <div onclick="selectMedia('{{ $item->file_path }}', '{{ addslashes($item->name) }}')"
                                style="border: 2px solid var(--border); border-radius: 10px; padding: 14px 10px; text-align: center; cursor: pointer; transition: border-color 0.15s, background 0.15s; background: var(--bg-surface);"
                                class="picker-card">
                                @if($item->type == 'image')
                                    <div style="font-size: 2rem; margin-bottom: 6px;">🖼️</div>
                                    <span style="font-size: 0.65rem; background: #dbeafe; color: #1d4ed8; padding: 2px 8px; border-radius: 20px; font-weight: 600;">Image</span>
                                @elseif($item->type == 'video')
                                    <div style="font-size: 2rem; margin-bottom: 6px;">🎥</div>
                                    <span style="font-size: 0.65rem; background: #fee2e2; color: #b91c1c; padding: 2px 8px; border-radius: 20px; font-weight: 600;">Vidéo</span>
                                @else
                                    <div style="font-size: 2rem; margin-bottom: 6px;">📄</div>
                                    <span style="font-size: 0.65rem; background: #f3f4f6; color: #374151; padding: 2px 8px; border-radius: 20px; font-weight: 600;">Document</span>
                                @endif
                                <div style="margin-top: 8px; font-size: 0.72rem; color: var(--text-1); font-weight: 600; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $item->name }}">{{ $item->name }}</div>
                                <div style="font-size: 0.65rem; color: var(--text-3);">{{ number_format($item->size / 1048576, 2) }} Mo</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div id="pickerGrid" style="text-align: center; padding: 40px 0; color: var(--text-3);">
                    <div style="font-size: 2.5rem; margin-bottom: 10px;">📂</div>
                    <div style="font-weight: 600; margin-bottom: 6px; color: var(--text-2);">La médiathèque est vide</div>
                    <div style="font-size: 0.85rem;">Cliquez sur <strong>Téléverser</strong> pour ajouter votre premier fichier.</div>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div style="padding: 12px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-start;">
            <button onclick="closeMediaPickerModal()" style="padding: 8px 18px; background: none; border: 1px solid var(--border); border-radius: 8px; color: var(--text-2); cursor: pointer; font-size: 0.85rem;">Annuler</button>
        </div>
    </div>
</div>

@push('styles')
<style>
.picker-card:hover {
    border-color: var(--primary) !important;
    background: rgba(var(--primary-rgb, 99, 102, 241), 0.05) !important;
}
#pickerSearchInput:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(var(--primary-rgb, 99, 102, 241), 0.12);
}
</style>
@endpush

@push('scripts')
<script>
function uploadFromPicker(input) {
    if (!input.files || !input.files[0]) return;

    const file = input.files[0];
    const formData = new FormData();
    formData.append('file', file);
    formData.append('_token', '{{ csrf_token() }}');

    const progressWrap = document.getElementById('pickerUploadProgress');
    const progressBar = document.getElementById('pickerProgressBar');
    const progressText = document.getElementById('pickerProgressText');
    progressWrap.style.display = 'flex';
    progressBar.style.width = '0%';
    progressText.textContent = '0%';

    const xhr = new XMLHttpRequest();
    xhr.upload.onprogress = function(e) {
        if (e.lengthComputable) {
            const pct = Math.round((e.loaded / e.total) * 100);
            progressBar.style.width = pct + '%';
            progressText.textContent = pct + '%';
        }
    };

    xhr.onload = function() {
        progressWrap.style.display = 'none';
        if (xhr.status >= 200 && xhr.status < 300) {
            try {
                const data = JSON.parse(xhr.responseText);
                if (data.success && data.media) {
                    addMediaCardToPicker(data.media);
                    input.value = '';
                }
            } catch(e) {
                alert('Erreur lors du traitement de la réponse.');
            }
        } else {
            alert("Erreur lors de l'upload.");
        }
    };

    xhr.onerror = function() { alert("Erreur de connexion."); progressWrap.style.display = 'none'; };
    xhr.open('POST', '{{ route('admin.media.store') }}', true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.send(formData);
}

function addMediaCardToPicker(media) {
    const grid = document.getElementById('pickerGrid');
    if (grid.querySelector('div[style*="text-align: center"]')) {
        grid.innerHTML = '';
        grid.className = 'row g-2';
    }

    const icons = { image: '🖼️', video: '🎥', document: '📄' };
    const badgeStyles = {
        image: 'background:#dbeafe;color:#1d4ed8',
        video: 'background:#fee2e2;color:#b91c1c',
        document: 'background:#f3f4f6;color:#374151'
    };
    const labels = { image: 'Image', video: 'Vidéo', document: 'Document' };

    const col = document.createElement('div');
    col.className = 'col-6 col-md-3 picker-media-item';
    col.setAttribute('data-name', media.name.toLowerCase());
    col.innerHTML = `
        <div onclick="selectMedia('${media.file_path}', '${media.name.replace(/'/g, "\\'")}')"
            style="border:2px solid var(--border);border-radius:10px;padding:14px 10px;text-align:center;cursor:pointer;transition:border-color 0.15s,background 0.15s;background:var(--bg-surface);"
            class="picker-card">
            <div style="font-size:2rem;margin-bottom:6px;">${icons[media.type] || '📄'}</div>
            <span style="font-size:0.65rem;${badgeStyles[media.type] || badgeStyles.document};padding:2px 8px;border-radius:20px;font-weight:600;">${labels[media.type] || 'Document'}</span>
            <div style="margin-top:8px;font-size:0.72rem;color:var(--text-1);font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="${media.name}">${media.name}</div>
            <div style="font-size:0.65rem;color:var(--text-3);">${media.size_formatted}</div>
        </div>`;

    grid.prepend(col);

    const countBadge = document.getElementById('pickerFileCount');
    if (countBadge) countBadge.textContent = document.querySelectorAll('.picker-media-item').length;
}
</script>
@endpush

@endsection
