@extends('admin.layout')

@section('title', 'Modifier une catégorie')

@section('content')
    <div class="card card-borderless p-4">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nom</label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $category->slug) }}" class="form-control">
                    <small class="text-muted">Laisser vide pour générer automatiquement.</small>
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Ordre</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" class="form-control" min="0">
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                <a href="{{ route('admin.categories') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
@endsection
