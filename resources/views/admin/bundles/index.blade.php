@extends('admin.layout')

@section('title', 'Gestion des Packs / Bundles')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;">
    <p style="color:var(--text-2);font-size:0.875rem;margin:0;">Regroupez plusieurs formations pour proposer des offres groupées à prix réduit.</p>
    <a href="{{ route('admin.bundles.create') }}" class="btn btn-primary">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Créer un Pack
    </a>
</div>

<div class="card card-borderless p-4">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Nom du Pack</th>
                    <th>Prix Promotionnel</th>
                    <th>Formations incluses</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bundles as $bundle)
                    <tr>
                        <td>
                            <strong>{{ $bundle->name }}</strong>
                            @if($bundle->description)
                                <small class="text-muted d-block">{{ Str::limit($bundle->description, 80) }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-primary" style="font-size:0.85rem;">
                                {{ number_format($bundle->price, 0, ',', ' ') }} CFA
                            </span>
                        </td>
                        <td>
                            <div style="display:flex;flex-wrap:wrap;gap:0.4rem;">
                                @foreach($bundle->trainings as $training)
                                    <span class="badge badge-muted">{{ Str::limit($training->title, 25) }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td style="text-align:right;white-space:nowrap;" class="table-actions">
                            <a href="{{ route('admin.bundles.edit', $bundle) }}" class="btn btn-sm btn-outline">Modifier</a>
                            <form action="{{ route('admin.bundles.destroy', $bundle) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer ce pack ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-5">
                            Aucun pack promotionnel n'est défini pour le moment.<br>
                            <a href="{{ route('admin.bundles.create') }}" class="btn btn-sm btn-primary mt-3">Créer votre premier Pack</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
