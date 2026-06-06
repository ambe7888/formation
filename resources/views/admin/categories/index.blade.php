@extends('admin.layout')

@section('title', 'Catégories')

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-success">Ajouter une catégorie</a>
    </div>
    <div class="card card-borderless p-4">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Slug</th>
                    <th>Description</th>
                    <th>Ordre</th>
                    <th>Formations</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->slug }}</td>
                        <td>{{ Str::limit($category->description, 60) }}</td>
                        <td>{{ $category->sort_order }}</td>
                        <td>{{ $category->trainings_count }}</td>
                        <td class="text-end table-actions">
                            <form action="{{ route('admin.categories.move-up', $category) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-secondary" {{ $loop->first ? 'disabled' : '' }}>↑</button>
                            </form>
                            <form action="{{ route('admin.categories.move-down', $category) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-secondary" {{ $loop->last ? 'disabled' : '' }}>↓</button>
                            </form>
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-primary">Modifier</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette catégorie ?');">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
