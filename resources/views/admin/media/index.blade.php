@extends('admin.layout')

@section('title', 'Médiathèque')

@push('styles')
<style>
    .upload-dropzone {
        border: 2px dashed var(--primary);
        background: var(--primary-dim);
        border-radius: 12px;
        padding: 2.2rem 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .upload-dropzone:hover {
        background: rgba(15, 118, 110, 0.15);
        border-color: #128B82;
        transform: translateY(-2px);
    }
    .upload-dropzone-icon {
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: var(--bg-card);
        color: var(--primary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.75rem;
        box-shadow: 0 4px 14px rgba(0,0,0,0.08);
        border: 1px solid var(--border);
    }
</style>
@endpush

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h2 class="h4 mb-1" style="font-weight: 700; color: var(--text-1);">Médiathèque</h2>
        <p class="text-muted small mb-0">Gestion centralisée des fichiers, vidéos et documents de vos formations.</p>
    </div>
    <button type="button" class="btn btn-primary" onclick="openMediaModal()">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        Nouveau Fichier
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger mb-4">
        <strong>Erreur d'upload :</strong>
        <ul class="mb-0 mt-1 small">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Filtres & Recherche -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mb-3">
    <div class="d-flex align-items-center gap-2">
        <button class="btn btn-sm btn-primary media-filter-btn" data-filter="all" onclick="filterMedia('all', this)">Tous ({{ $media->count() }})</button>
        <button class="btn btn-sm btn-secondary media-filter-btn" data-filter="video" onclick="filterMedia('video', this)">Vidéos ({{ $media->where('type', 'video')->count() }})</button>
        <button class="btn btn-sm btn-secondary media-filter-btn" data-filter="document" onclick="filterMedia('document', this)">Documents ({{ $media->where('type', 'document')->count() }})</button>
        <button class="btn btn-sm btn-secondary media-filter-btn" data-filter="image" onclick="filterMedia('image', this)">Images ({{ $media->where('type', 'image')->count() }})</button>
    </div>

    <div style="position: relative; min-width: 250px;">
        <input type="text" id="searchInput" class="form-control" placeholder="Rechercher..." onkeyup="searchMedia()" style="padding-left: 2.2rem;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); opacity: 0.5;">
            <circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
    </div>
</div>

<!-- Liste des fichiers -->
<div class="card card-borderless p-0 overflow-hidden">
    @if($media->isEmpty())
        <div class="text-center py-5 text-muted p-4">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mb-2" style="opacity: 0.4;">
                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
            </svg>
            <p class="m-0">Aucun fichier présent dans la médiathèque.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table align-middle m-0" id="mediaTable">
                <thead>
                    <tr>
                        <th style="width: 45%;">Nom du fichier</th>
                        <th style="width: 15%;">Type</th>
                        <th style="width: 15%;">Taille</th>
                        <th style="width: 15%;">Ajouté le</th>
                        <th class="text-end" style="width: 10%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($media as $item)
                        <tr data-type="{{ $item->type }}" data-name="{{ strtolower($item->name) }}">
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($item->type == 'image')
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary" style="flex-shrink:0;"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                    @elseif($item->type == 'video')
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--danger); flex-shrink:0;"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
                                    @else
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-muted" style="flex-shrink:0;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                                    @endif
                                    <div class="text-truncate">
                                        <div class="fw-semibold text-truncate" title="{{ $item->name }}" style="color: var(--text-1);">{{ $item->name }}</div>
                                        <div class="text-muted small text-truncate" style="font-size: 0.72rem;">{{ $item->file_path }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($item->type == 'image')
                                    <span class="badge badge-primary">Image</span>
                                @elseif($item->type == 'video')
                                    <span class="badge badge-danger">Vidéo</span>
                                @else
                                    <span class="badge badge-muted">Document</span>
                                @endif
                            </td>
                            <td class="text-muted small font-mono">
                                {{ number_format($item->size / 1048576, 2) }} Mo
                            </td>
                            <td class="text-muted small">
                                {{ $item->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ Storage::url($item->file_path) }}" target="_blank" class="btn btn-sm btn-outline" title="Aperçu / Ouvrir">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                        Ouvrir
                                    </a>
                                    <form action="{{ route('admin.media.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce fichier ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger-outline" title="Supprimer">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<!-- Native Admin Overlay Modal -->
<div id="mediaUploadModal" class="modal-overlay" onclick="closeMediaModal(event)">
    <div class="modal-container" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h5 class="modal-title">Ajouter un fichier</h5>
            <button type="button" class="modal-close" onclick="closeMediaModal()">&times;</button>
        </div>
        <form id="mediaUploadForm" action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                
                <!-- Zone de dropzone normale -->
                <div id="uploadDropzoneArea">
                    <p class="text-muted small mb-3 text-center">Taille max autorisée par votre serveur : <strong>{{ ini_get('upload_max_filesize') }}</strong></p>
                    
                    <div class="upload-dropzone" onclick="document.getElementById('fileInputReal').click()">
                        <input type="file" id="fileInputReal" name="file" class="d-none" required onchange="handleFileSelected(this)">
                        
                        <div class="upload-dropzone-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                        </div>
                        
                        <div id="dropzoneText">
                            <h6 class="mb-1" style="font-weight: 700; color: var(--text-1);">Cliquez pour parcourir un fichier</h6>
                            <span class="text-muted small">L'upload démarrera immédiatement après la sélection</span>
                        </div>
                    </div>
                </div>

                <!-- Section du Cercle de Chargement de Progression (Affiché lors de l'upload) -->
                <div id="uploadProgressSection" class="d-none text-center py-4">
                    <div class="d-inline-flex align-items-center justify-content-center mb-3" style="position: relative; width: 100px; height: 100px; margin: 0 auto;">
                        <svg width="100" height="100" viewBox="0 0 100 100" style="position: absolute; top: 0; left: 0;">
                            <circle stroke="var(--border)" stroke-width="8" fill="transparent" r="40" cx="50" cy="50"/>
                            <circle id="progressCircle" stroke="var(--primary)" stroke-width="8" stroke-dasharray="251.32" stroke-dashoffset="251.32" stroke-linecap="round" fill="transparent" r="40" cx="50" cy="50" style="transition: stroke-dashoffset 0.15s ease-in-out; transform: rotate(-90deg); transform-origin: 50% 50%;"/>
                        </svg>
                        <span id="uploadPercentageText" class="fw-bold" style="position: relative; z-index: 10; font-size: 1.1rem; color: var(--text-1); line-height: 1;">0%</span>
                    </div>
                    <div class="fw-semibold" style="color: var(--text-1); font-size: 0.95rem;">Téléversement en cours...</div>
                    <div class="text-muted small mt-1" id="uploadStatusSub">Veuillez patienter pendant le transfert du fichier</div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4" id="modalFooterActions">
                    <button type="button" class="btn btn-secondary" onclick="closeMediaModal()">Annuler</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function openMediaModal() {
        // Reset UI
        document.getElementById('uploadDropzoneArea').classList.remove('d-none');
        document.getElementById('modalFooterActions').classList.remove('d-none');
        document.getElementById('uploadProgressSection').classList.add('d-none');
        document.getElementById('fileInputReal').value = '';
        
        document.getElementById('mediaUploadModal').classList.add('active');
    }

    function closeMediaModal(e) {
        if (!e || e.target === document.getElementById('mediaUploadModal') || e.target.classList.contains('modal-close') || e.target.closest('.btn-secondary')) {
            document.getElementById('mediaUploadModal').classList.remove('active');
        }
    }

    // Déclencher l'upload immédiatement à la sélection du fichier
    function handleFileSelected(input) {
        if (input.files && input.files[0]) {
            startFileUpload();
        }
    }

    function startFileUpload() {
        const fileInput = document.getElementById('fileInputReal');
        if (!fileInput.files || !fileInput.files.length) return;

        const form = document.getElementById('mediaUploadForm');
        const formData = new FormData(form);
        const xhr = new XMLHttpRequest();

        // Afficher immédiatement l'animation du cercle
        document.getElementById('uploadDropzoneArea').classList.add('d-none');
        document.getElementById('modalFooterActions').classList.add('d-none');
        document.getElementById('uploadProgressSection').classList.remove('d-none');

        const circle = document.getElementById('progressCircle');
        const radius = circle.r.baseVal.value;
        const circumference = 2 * Math.PI * radius; // 251.32
        circle.style.strokeDasharray = `${circumference} ${circumference}`;

        function setProgress(percent) {
            const offset = circumference - (percent / 100 * circumference);
            circle.style.strokeDashoffset = offset;
            document.getElementById('uploadPercentageText').textContent = Math.round(percent) + '%';
        }

        xhr.upload.onprogress = function(event) {
            if (event.lengthComputable) {
                const percent = (event.loaded / event.total) * 100;
                setProgress(percent);
            }
        };

        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                setProgress(100);
                document.getElementById('uploadStatusSub').textContent = "Terminé avec succès ! Rechargement...";
                setTimeout(() => {
                    window.location.reload();
                }, 400);
            } else {
                alert("Erreur lors de l'envoi du fichier.");
                window.location.reload();
            }
        };

        xhr.onerror = function() {
            alert("Erreur de connexion lors de l'envoi.");
            window.location.reload();
        };

        xhr.open('POST', form.action, true);
        xhr.send(formData);
    }

    @if ($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            openMediaModal();
        });
    @endif

    let currentFilter = 'all';

    function filterMedia(type, btn) {
        currentFilter = type;
        document.querySelectorAll('.media-filter-btn').forEach(b => {
            b.classList.remove('btn-primary');
            b.classList.add('btn-secondary');
        });
        btn.classList.remove('btn-secondary');
        btn.classList.add('btn-primary');
        applyFilters();
    }

    function searchMedia() {
        applyFilters();
    }

    function applyFilters() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('#mediaTable tbody tr');

        rows.forEach(row => {
            const rowType = row.getAttribute('data-type');
            const rowName = row.getAttribute('data-name');

            const matchesType = (currentFilter === 'all' || rowType === currentFilter);
            const matchesQuery = rowName.includes(query);

            if (matchesType && matchesQuery) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endsection
