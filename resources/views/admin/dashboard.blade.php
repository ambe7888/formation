@extends('admin.layout')

@section('title', 'Tableau de bord')

@section('content')

{{-- ─── KPI Cards ────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.25rem;margin-bottom:1.75rem;">

    {{-- Formations actives --}}
    <div class="card p-4" style="border-left:3px solid var(--primary);">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.75rem;">
            <div style="width:40px;height:40px;border-radius:10px;background:var(--primary-dim);display:flex;align-items:center;justify-content:center;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                </svg>
            </div>
            <span class="badge badge-primary">Actives</span>
        </div>
        <div style="font-size:2rem;font-weight:800;color:var(--text-1);line-height:1;">{{ $activeTrainings }}</div>
        <div style="font-size:0.82rem;color:var(--text-3);margin-top:0.35rem;">Formations publiées</div>
    </div>

    {{-- Slides hero --}}
    <div class="card p-4" style="border-left:3px solid var(--info);">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.75rem;">
            <div style="width:40px;height:40px;border-radius:10px;background:var(--info-dim);display:flex;align-items:center;justify-content:center;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--info)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="7" width="20" height="15" rx="2"/><polyline points="17 2 12 7 7 2"/>
                </svg>
            </div>
            <span class="badge badge-info">Slider</span>
        </div>
        <div style="font-size:2rem;font-weight:800;color:var(--text-1);line-height:1;">{{ $featuredTrainings }}</div>
        <div style="font-size:0.82rem;color:var(--text-3);margin-top:0.35rem;">Slides hero actifs</div>
    </div>

    {{-- Catégories --}}
    <div class="card p-4" style="border-left:3px solid var(--accent);">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.75rem;">
            <div style="width:40px;height:40px;border-radius:10px;background:var(--accent-dim);display:flex;align-items:center;justify-content:center;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </div>
            <span class="badge badge-success">Tri</span>
        </div>
        <div style="font-size:2rem;font-weight:800;color:var(--text-1);line-height:1;">{{ $categories->count() }}</div>
        <div style="font-size:0.82rem;color:var(--text-3);margin-top:0.35rem;">Catégories définies</div>
    </div>

    {{-- Inscriptions --}}
    <div class="card p-4" style="border-left:3px solid var(--warning);">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.75rem;">
            <div style="width:40px;height:40px;border-radius:10px;background:var(--warning-dim);display:flex;align-items:center;justify-content:center;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--warning)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <span class="badge badge-warning">Clients</span>
        </div>
        <div style="font-size:2rem;font-weight:800;color:var(--text-1);line-height:1;">{{ $registrations }}</div>
        <div style="font-size:0.82rem;color:var(--text-3);margin-top:0.35rem;">Inscriptions reçues</div>
    </div>

    {{-- Paiements --}}
    <div class="card p-4" style="border-left:3px solid #8B5CF6;">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.75rem;">
            <div style="width:40px;height:40px;border-radius:10px;background:rgba(139,92,246,0.1);display:flex;align-items:center;justify-content:center;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8B5CF6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
                </svg>
            </div>
            <span style="display:inline-flex;align-items:center;padding:0.2rem 0.6rem;border-radius:999px;font-size:0.72rem;font-weight:600;background:rgba(139,92,246,0.1);color:#A78BFA;border:1px solid rgba(139,92,246,0.2);">Paiements</span>
        </div>
        <div style="font-size:2rem;font-weight:800;color:var(--text-1);line-height:1;">{{ $payments }}</div>
        <div style="font-size:0.82rem;color:var(--text-3);margin-top:0.35rem;">Paiements enregistrés</div>
    </div>
</div>

{{-- ─── Bottom Grid ─────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">

    {{-- Navigation rapide --}}
    <div class="card p-4">
        <h5 style="margin-bottom:1rem;">Navigation rapide</h5>
        <div style="display:flex;flex-direction:column;gap:0.25rem;">
            <a href="{{ route('admin.categories') }}" style="display:flex;align-items:center;gap:0.75rem;padding:0.65rem 0.85rem;border-radius:9px;color:var(--text-2);text-decoration:none;font-size:0.875rem;font-weight:500;border:1px solid transparent;transition:all 150ms ease;"
               onmouseover="this.style.background='var(--bg-hover)';this.style.color='var(--text-1)';"
               onmouseout="this.style.background='';this.style.color='var(--text-2)';">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity:0.6"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                Gérer les catégories
            </a>
            <a href="{{ route('admin.trainings') }}" style="display:flex;align-items:center;gap:0.75rem;padding:0.65rem 0.85rem;border-radius:9px;color:var(--text-2);text-decoration:none;font-size:0.875rem;font-weight:500;border:1px solid transparent;transition:all 150ms ease;"
               onmouseover="this.style.background='var(--bg-hover)';this.style.color='var(--text-1)';"
               onmouseout="this.style.background='';this.style.color='var(--text-2)';">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity:0.6"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                Gérer les formations
            </a>
            <a href="{{ route('admin.bundles') }}" style="display:flex;align-items:center;gap:0.75rem;padding:0.65rem 0.85rem;border-radius:9px;color:var(--text-2);text-decoration:none;font-size:0.875rem;font-weight:500;border:1px solid transparent;transition:all 150ms ease;"
               onmouseover="this.style.background='var(--bg-hover)';this.style.color='var(--text-1)';"
               onmouseout="this.style.background='';this.style.color='var(--text-2)';">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity:0.6"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                Gérer les packs
            </a>
            <a href="{{ route('admin.registrations') }}" style="display:flex;align-items:center;gap:0.75rem;padding:0.65rem 0.85rem;border-radius:9px;color:var(--text-2);text-decoration:none;font-size:0.875rem;font-weight:500;border:1px solid transparent;transition:all 150ms ease;"
               onmouseover="this.style.background='var(--bg-hover)';this.style.color='var(--text-1)';"
               onmouseout="this.style.background='';this.style.color='var(--text-2)';">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity:0.6"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                Voir les inscriptions
            </a>
            <a href="{{ route('admin.payments') }}" style="display:flex;align-items:center;gap:0.75rem;padding:0.65rem 0.85rem;border-radius:9px;color:var(--text-2);text-decoration:none;font-size:0.875rem;font-weight:500;border:1px solid transparent;transition:all 150ms ease;"
               onmouseover="this.style.background='var(--bg-hover)';this.style.color='var(--text-1)';"
               onmouseout="this.style.background='';this.style.color='var(--text-2)';">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity:0.6"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                Voir les paiements
            </a>
        </div>
    </div>

    {{-- Ordre des sections --}}
    <div class="card p-4">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
            <h5 style="margin:0;">Ordre des sections</h5>
            <a href="{{ route('admin.categories') }}" class="btn btn-outline btn-sm">Modifier</a>
        </div>
        @forelse($categories as $category)
            <div style="display:flex;justify-content:space-between;align-items:center;padding:0.55rem 0;border-bottom:1px solid var(--border);">
                <span style="font-size:0.875rem;color:var(--text-2);">{{ $category->name }}</span>
                <span style="display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:6px;background:var(--bg-hover);font-size:0.72rem;font-weight:700;color:var(--text-3);font-family:'Fira Code',monospace;">{{ $category->sort_order }}</span>
            </div>
        @empty
            <p style="color:var(--text-3);font-size:0.875rem;">Aucune catégorie définie.</p>
        @endforelse
    </div>
</div>

@endsection
