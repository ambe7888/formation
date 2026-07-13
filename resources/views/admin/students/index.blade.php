@extends('admin.layout')

@section('title', 'Comptes Étudiants')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-dark">Comptes Étudiants</h1>
    <button onclick="openCreateModal()" class="btn btn-primary">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        Nouveau compte étudiant
    </button>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="border-0 px-4 py-3">ID</th>
                        <th class="border-0 px-4 py-3">Nom</th>
                        <th class="border-0 px-4 py-3">Email</th>
                        <th class="border-0 px-4 py-3">Téléphone</th>
                        <th class="border-0 px-4 py-3">Statut</th>
                        <th class="border-0 px-4 py-3">Date de création</th>
                        <th class="border-0 px-4 py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                    <tr>
                        <td class="px-4 py-3 text-muted">#{{ $student->id }}</td>
                        <td class="px-4 py-3 fw-bold">{{ $student->name }}</td>
                        <td class="px-4 py-3">{{ $student->email }}</td>
                        <td class="px-4 py-3">{{ $student->phone ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @if($student->is_active)
                                <span class="status-badge status-active">Actif</span>
                            @else
                                <span class="status-badge status-inactive">Désactivé</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-muted">{{ $student->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-end table-actions">
                            <button onclick="openEditModal(this)" 
                                    data-id="{{ $student->id }}"
                                    data-name="{{ $student->name }}"
                                    data-email="{{ $student->email }}"
                                    data-phone="{{ $student->phone }}"
                                    data-active="{{ $student->is_active ? '1' : '0' }}"
                                    class="btn btn-sm btn-outline-primary">
                                Modifier
                            </button>
                            <form action="{{ route('admin.students.destroy', $student) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce compte étudiant ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger-outline">
                                    Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($students->isEmpty())
        <div class="text-center p-5 text-muted">
            <p>Aucun compte étudiant trouvé.</p>
        </div>
        @endif
    </div>
</div>

<!-- ── MODAL: CREATE STUDENT ── -->
<div class="modal-overlay" id="createStudentModal" onclick="closeCreateModal(event)">
    <div class="modal-container" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 class="modal-title">Ajouter un étudiant</h3>
            <button class="modal-close" onclick="closeCreateModal()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin.students.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="create_name" class="form-label">Nom complet</label>
                    <input type="text" class="form-control @error('name') @if(session('open_create_modal')) is-invalid @endif @enderror" id="create_name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        @if(session('open_create_modal'))
                            <div class="invalid-feedback text-danger text-sm mt-1">{{ $message }}</div>
                        @endif
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="create_email" class="form-label">Adresse Email</label>
                    <input type="email" class="form-control @error('email') @if(session('open_create_modal')) is-invalid @endif @enderror" id="create_email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        @if(session('open_create_modal'))
                            <div class="invalid-feedback text-danger text-sm mt-1">{{ $message }}</div>
                        @endif
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="create_phone" class="form-label">Téléphone</label>
                    <input type="text" class="form-control @error('phone') @if(session('open_create_modal')) is-invalid @endif @enderror" id="create_phone" name="phone" value="{{ old('phone') }}">
                    @error('phone')
                        @if(session('open_create_modal'))
                            <div class="invalid-feedback text-danger text-sm mt-1">{{ $message }}</div>
                        @endif
                    @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="create_password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control @error('password') @if(session('open_create_modal')) is-invalid @endif @enderror" id="create_password" name="password" required>
                        @error('password')
                            @if(session('open_create_modal'))
                                <div class="invalid-feedback text-danger text-sm mt-1">{{ $message }}</div>
                            @endif
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="create_password_confirmation" class="form-label">Confirmer le mot de passe</label>
                        <input type="password" class="form-control" id="create_password_confirmation" name="password_confirmation" required>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                    <button type="button" class="btn btn-secondary me-2" onclick="closeCreateModal(event)">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer le compte</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ── MODAL: EDIT STUDENT ── -->
<div class="modal-overlay" id="editStudentModal" onclick="closeEditModal(event)">
    <div class="modal-container" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 class="modal-title">Modifier le compte étudiant</h3>
            <button class="modal-close" onclick="closeEditModal()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <div class="modal-body">
            <form id="edit_student_form" action="" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="edit_name" class="form-label">Nom complet</label>
                    <input type="text" class="form-control @error('name') @if(session('open_edit_modal')) is-invalid @endif @enderror" id="edit_name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        @if(session('open_edit_modal'))
                            <div class="invalid-feedback text-danger text-sm mt-1">{{ $message }}</div>
                        @endif
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="edit_email" class="form-label">Adresse Email</label>
                    <input type="email" class="form-control @error('email') @if(session('open_edit_modal')) is-invalid @endif @enderror" id="edit_email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        @if(session('open_edit_modal'))
                            <div class="invalid-feedback text-danger text-sm mt-1">{{ $message }}</div>
                        @endif
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="edit_phone" class="form-label">Téléphone</label>
                    <input type="text" class="form-control @error('phone') @if(session('open_edit_modal')) is-invalid @endif @enderror" id="edit_phone" name="phone" value="{{ old('phone') }}">
                    @error('phone')
                        @if(session('open_edit_modal'))
                            <div class="invalid-feedback text-danger text-sm mt-1">{{ $message }}</div>
                        @endif
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="edit_is_active" class="form-label">Statut du compte</label>
                    <select class="form-select @error('is_active') @if(session('open_edit_modal')) is-invalid @endif @enderror" id="edit_is_active" name="is_active" required>
                        <option value="1">Actif (accès autorisé)</option>
                        <option value="0">Désactivé (accès bloqué)</option>
                    </select>
                    @error('is_active')
                        @if(session('open_edit_modal'))
                            <div class="invalid-feedback text-danger text-sm mt-1">{{ $message }}</div>
                        @endif
                    @enderror
                </div>

                <div class="alert alert-warning text-sm mb-3">
                    <strong>Attention :</strong> Laissez les champs de mot de passe vides si vous ne souhaitez pas modifier le mot de passe actuel de l'étudiant.
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="edit_password" class="form-label">Nouveau mot de passe</label>
                        <input type="password" class="form-control @error('password') @if(session('open_edit_modal')) is-invalid @endif @enderror" id="edit_password" name="password">
                        @error('password')
                            @if(session('open_edit_modal'))
                                <div class="invalid-feedback text-danger text-sm mt-1">{{ $message }}</div>
                            @endif
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="edit_password_confirmation" class="form-label">Confirmer le mot de passe</label>
                        <input type="password" class="form-control" id="edit_password_confirmation" name="password_confirmation">
                    </div>
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
    const createModal = document.getElementById('createStudentModal');
    const editModal = document.getElementById('editStudentModal');

    function openCreateModal() {
        createModal.classList.add('active');
    }

    function closeCreateModal(e) {
        if (e && e.target !== createModal && !e.target.closest('.modal-close') && !e.target.closest('.btn-secondary')) return;
        createModal.classList.remove('active');
    }

    function openEditModal(btn) {
        const id = btn.getAttribute('data-id');
        const name = btn.getAttribute('data-name');
        const email = btn.getAttribute('data-email');
        const phone = btn.getAttribute('data-phone');
        const isActive = btn.getAttribute('data-active');

        // Set action url
        document.getElementById('edit_student_form').action = `{{ url('/admin/students') }}/${id}`;
        
        // Fill form fields
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_phone').value = (!phone || phone === 'null') ? '' : phone;
        document.getElementById('edit_is_active').value = isActive;

        // Reset password fields
        document.getElementById('edit_password').value = '';
        document.getElementById('edit_password_confirmation').value = '';

        editModal.classList.add('active');
    }

    function closeEditModal(e) {
        if (e && e.target !== editModal && !e.target.closest('.modal-close') && !e.target.closest('.btn-secondary')) return;
        editModal.classList.remove('active');
    }

    // Auto-reopen modals if validation errors occur
    @if(session('open_create_modal'))
        openCreateModal();
    @endif

    @if(session('open_edit_modal'))
        // Re-find the button or manually trigger filling with old values
        document.addEventListener('DOMContentLoaded', function() {
            const studentId = "{{ session('open_edit_modal') }}";
            const btn = document.querySelector(`button[data-id="${studentId}"]`);
            if (btn) {
                openEditModal(btn);
                // Override with user's old input values so they don't lose their input
                document.getElementById('edit_name').value = "{{ old('name') }}";
                document.getElementById('edit_email').value = "{{ old('email') }}";
                document.getElementById('edit_phone').value = "{{ old('phone') }}";
                document.getElementById('edit_is_active').value = "{{ old('is_active') }}";
            }
        });
    @endif
</script>
@endsection
