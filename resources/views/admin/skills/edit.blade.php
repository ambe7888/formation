@extends('admin.layout')

@section('title', 'Modifier la compétence')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-borderless">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title mb-0">Modifier : {{ $skill->name }}</h5>
                    <a href="{{ route('admin.skills') }}" class="btn btn-sm btn-outline-secondary">Retour à la liste</a>
                </div>
                <form action="{{ route('admin.skills.update', $skill) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Titre de la compétence *</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $skill->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug</label>
                        <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug', $skill->slug) }}">
                    </div>
                    <div class="mb-3">
                        <label for="badge_color" class="form-label">Couleur du Badge *</label>
                        <div class="d-flex align-items-center">
                            <input type="color" class="form-control form-control-color me-3" id="badge_color" name="badge_color" value="{{ old('badge_color', $skill->badge_color) }}" title="Choisir une couleur">
                            <span class="text-muted">Cliquez pour choisir une couleur</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('admin.skills') }}" class="btn btn-light me-2">Annuler</a>
                        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
