@extends('admin.layout')

@section('title', 'Formations')

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <a href="{{ route('admin.trainings.create') }}" class="btn btn-success">Ajouter une formation</a>
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
                    <tr>
                        <td>{{ $training->title }}</td>
                        <td>{{ $training->category?->name ?? $training->category }}</td>
                        <td>{{ $training->start_date->format('d/m/Y') }}</td>
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
                            <a href="{{ route('admin.trainings.edit', $training) }}" class="btn btn-sm btn-primary">Modifier</a>
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
@endsection