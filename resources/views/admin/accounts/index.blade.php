@extends('admin.layout')

@section('title', 'Comptes Administrateurs')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-dark">Comptes Administrateurs</h1>
    <a href="{{ route('admin.accounts.create') }}" class="btn btn-primary">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        Nouveau compte
    </a>
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
                        <th class="border-0 px-4 py-3">Date de création</th>
                        <th class="border-0 px-4 py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($admins as $admin)
                    <tr>
                        <td class="px-4 py-3 text-muted">#{{ $admin->id }}</td>
                        <td class="px-4 py-3 fw-bold">{{ $admin->name }}</td>
                        <td class="px-4 py-3">{{ $admin->email }}</td>
                        <td class="px-4 py-3 text-muted">{{ $admin->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-end table-actions">
                            <a href="{{ route('admin.accounts.edit', $admin) }}" class="btn btn-sm btn-outline-primary">
                                Modifier
                            </a>
                            @if(auth()->id() !== $admin->id)
                            <form action="{{ route('admin.accounts.destroy', $admin) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce compte administrateur ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger-outline">
                                    Supprimer
                                </button>
                            </form>
                            @else
                                <span class="badge bg-secondary ms-2" title="Vous ne pouvez pas vous supprimer vous-même">Vous</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($admins->isEmpty())
        <div class="text-center p-5 text-muted">
            <p>Aucun compte administrateur trouvé.</p>
        </div>
        @endif
    </div>
</div>
@endsection
