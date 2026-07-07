@extends('admin.layout')

@section('title', 'Gestion des Inscriptions')

@section('content')

{{-- ─── Onglets custom (sans Bootstrap JS) ─────────────── --}}
<div style="display:flex;gap:0.5rem;margin-bottom:1.5rem;border-bottom:2px solid var(--border);padding-bottom:0;">
    <button id="tab-btn-active" onclick="switchTab('active')"
        style="padding:0.6rem 1.25rem;border:none;background:none;font-family:'Fira Sans',sans-serif;font-size:0.9rem;font-weight:600;cursor:pointer;color:var(--primary);border-bottom:2px solid var(--primary);margin-bottom:-2px;transition:all 150ms;">
        Inscriptions actives
        <span style="display:inline-flex;align-items:center;padding:0.1rem 0.5rem;border-radius:999px;font-size:0.7rem;background:var(--primary-dim);color:var(--primary);margin-left:0.4rem;">{{ $activeRegistrations->count() }}</span>
    </button>
    <button id="tab-btn-canceled" onclick="switchTab('canceled')"
        style="padding:0.6rem 1.25rem;border:none;background:none;font-family:'Fira Sans',sans-serif;font-size:0.9rem;font-weight:600;cursor:pointer;color:var(--text-2);border-bottom:2px solid transparent;margin-bottom:-2px;transition:all 150ms;">
        Historique & Annulations
        <span style="display:inline-flex;align-items:center;padding:0.1rem 0.5rem;border-radius:999px;font-size:0.7rem;background:var(--bg-hover);color:var(--text-3);margin-left:0.4rem;">{{ $canceledRegistrations->count() }}</span>
    </button>
</div>

{{-- ─── Onglet : Actives ────────────────────────────────── --}}
<div id="tab-active">
    <div class="card p-4">
        <h5 style="margin-bottom:1.25rem;">Liste des inscriptions actives</h5>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Formation / Pack</th>
                        <th>Notes</th>
                        <th>Finances</th>
                        <th>Paiement</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeRegistrations as $registration)
                        <tr>
                            {{-- Client --}}
                            <td>
                                <strong>{{ optional($registration->client)->name ?? 'N/A' }}</strong>
                                <div style="font-size:0.75rem;color:var(--text-3);margin-top:2px;">{{ optional($registration->client)->phone ?? '' }}</div>
                                <div style="font-size:0.75rem;color:var(--text-3);">{{ optional($registration->client)->email ?? '' }}</div>
                            </td>

                            {{-- Formation / Pack --}}
                            <td>
                                @if($registration->bundle_id)
                                    <span style="display:inline-flex;align-items:center;padding:0.25rem 0.65rem;border-radius:8px;font-size:0.8rem;font-weight:600;background:rgba(139,92,246,0.1);color:#8B5CF6;border:1px solid rgba(139,92,246,0.2);">
                                        Pack : {{ optional($registration->bundle)->name ?? 'N/A' }}
                                    </span>
                                @else
                                    <span class="badge badge-muted" style="font-size:0.8rem;">
                                        {{ optional($registration->training)->title ?? 'N/A' }}
                                    </span>
                                @endif
                            </td>

                            {{-- Notes --}}
                            <td>
                                @php $notes = json_decode($registration->notes ?? '[]', true) ?: []; @endphp
                                @if(!empty($notes))
                                    <div style="font-size:0.78rem;color:var(--text-2);">
                                        @if(isset($notes['month']))
                                            <div><strong style="color:var(--text-1);">Mois :</strong> {{ $notes['month'] }}</div>
                                        @endif
                                        @if(!empty($notes['message']))
                                            <div style="color:var(--text-3);font-style:italic;max-width:200px;white-space:normal;margin-top:2px;">"{{ Str::limit($notes['message'], 60) }}"</div>
                                        @endif
                                    </div>
                                @else
                                    <span style="color:var(--text-3);">—</span>
                                @endif
                            </td>

                            {{-- Finances --}}
                            <td style="font-size:0.8rem;">
                                <div><span style="color:var(--text-3);">Total :</span> <strong>{{ number_format($registration->amount, 0, ',', ' ') }}</strong></div>
                                <div><span style="color:var(--accent);">Versé :</span> <strong>{{ number_format($registration->amount_paid, 0, ',', ' ') }}</strong></div>
                                <div>
                                    <span style="color:var(--text-3);">Reste :</span>
                                    <strong style="color:{{ $registration->balance_due > 0 ? 'var(--danger)' : 'var(--text-3)' }};">
                                        {{ number_format($registration->balance_due, 0, ',', ' ') }}
                                    </strong>
                                </div>
                            </td>

                            {{-- Statut paiement --}}
                            <td>
                                @php $ps = $registration->payment_status; @endphp
                                @if($ps === 'paid')
                                    <span class="badge badge-success">Payé</span>
                                @elseif($ps === 'partial')
                                    <span class="badge badge-warning">Partiel</span>
                                @else
                                    <span class="badge badge-danger">Non payé</span>
                                @endif
                            </td>

                            {{-- Statut inscription --}}
                            <td>
                                <form action="{{ route('admin.registrations.status', $registration) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="form-select" onchange="this.form.submit()"
                                        style="font-size:0.8rem;padding:0.3rem 0.6rem;width:auto;min-width:130px;">
                                        <option value="pending"   {{ $registration->status === 'pending'   ? 'selected' : '' }}>En attente</option>
                                        <option value="confirmed" {{ $registration->status === 'confirmed' ? 'selected' : '' }}>Confirmée</option>
                                        <option value="canceled"  {{ $registration->status === 'canceled'  ? 'selected' : '' }}>Annuler</option>
                                    </select>
                                </form>
                            </td>

                            {{-- Date --}}
                            <td style="font-size:0.75rem;color:var(--text-3);white-space:nowrap;">
                                {{ optional($registration->created_at)->format('d/m/Y H:i') }}
                            </td>

                            {{-- Action (Oeil) --}}
                            @php
                                $tTitle = $registration->training ? $registration->training->title : ($registration->bundle ? $registration->bundle->name : 'N/A');
                                $modalData = json_encode([
                                    'clientName' => $registration->client->name ?? 'N/A',
                                    'clientEmail' => $registration->client->email ?? 'N/A',
                                    'clientPhone' => $registration->client->phone ?? '',
                                    'trainingTitle' => $tTitle,
                                    'date' => $registration->created_at->format('d/m/Y'),
                                    'price' => number_format($registration->amount, 0, ',', ' ') . ' FCFA',
                                    'paid' => number_format($registration->amount_paid, 0, ',', ' ') . ' FCFA',
                                    'remaining' => number_format($registration->balance_due, 0, ',', ' ') . ' FCFA',
                                    'remainingVal' => $registration->balance_due
                                ]);
                            @endphp
                            <td class="text-end table-actions">
                                <button type="button" class="btn btn-sm btn-outline-light" title="Voir les détails" onclick="openRegistrationModal(JSON.parse(this.getAttribute('data-modal')))" data-modal="{{ $modalData }}">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;color:var(--text-3);padding:3rem 1rem;">
                                Aucune inscription active.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ─── Onglet : Annulations ────────────────────────────── --}}
<div id="tab-canceled" style="display:none;">
    <div class="card p-4">
        <h5 style="margin-bottom:1.25rem;">Historique des annulations</h5>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Formation / Pack</th>
                        <th>Annulation</th>
                        <th>Finances</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($canceledRegistrations as $registration)
                        <tr>
                            <td>
                                <strong>{{ optional($registration->client)->name ?? 'N/A' }}</strong>
                                <div style="font-size:0.75rem;color:var(--text-3);">{{ optional($registration->client)->email ?? '' }}</div>
                            </td>
                            <td>
                                @if($registration->bundle_id)
                                    <span style="display:inline-flex;padding:0.25rem 0.65rem;border-radius:8px;font-size:0.8rem;font-weight:600;background:rgba(139,92,246,0.1);color:#8B5CF6;border:1px solid rgba(139,92,246,0.2);">
                                        Pack : {{ optional($registration->bundle)->name ?? 'N/A' }}
                                    </span>
                                @else
                                    <span class="badge badge-muted" style="font-size:0.8rem;">
                                        {{ optional($registration->training)->title ?? 'N/A' }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                @php $notes = json_decode($registration->notes ?? '[]', true) ?: []; @endphp
                                <div style="font-size:0.78rem;">
                                    <div style="font-weight:600;color:var(--badge-danger-text);">
                                        Annulée le : {{ isset($notes['canceled_at']) ? \Carbon\Carbon::parse($notes['canceled_at'])->format('d/m/Y H:i') : optional($registration->updated_at)->format('d/m/Y H:i') }}
                                    </div>
                                    <div style="color:var(--text-3);margin-top:2px;">
                                        Par :
                                        @if(!empty($notes['canceled_by_student']))
                                            Étudiant
                                        @else
                                            Administrateur
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td style="font-size:0.78rem;color:var(--text-3);">
                                <div>Total : {{ number_format($registration->amount, 0, ',', ' ') }}</div>
                                <div>Versé : {{ number_format($registration->amount_paid, 0, ',', ' ') }}</div>
                            </td>
                            <td><span class="badge badge-danger">Annulée</span></td>
                            <td style="font-size:0.75rem;color:var(--text-3);white-space:nowrap;">
                                {{ optional($registration->created_at)->format('d/m/Y H:i') }}
                            </td>
                            <td>
                                @php
                                    $tTitle = $registration->training ? $registration->training->title : ($registration->bundle ? $registration->bundle->name : 'N/A');
                                    $modalData = json_encode([
                                        'clientName' => $registration->client->name ?? 'N/A',
                                        'clientEmail' => $registration->client->email ?? 'N/A',
                                        'clientPhone' => $registration->client->phone ?? '',
                                        'trainingTitle' => $tTitle,
                                        'date' => $registration->created_at->format('d/m/Y'),
                                        'price' => number_format($registration->amount, 0, ',', ' ') . ' FCFA',
                                        'paid' => number_format($registration->amount_paid, 0, ',', ' ') . ' FCFA',
                                        'remaining' => number_format($registration->balance_due, 0, ',', ' ') . ' FCFA',
                                        'remainingVal' => $registration->balance_due
                                    ]);
                                @endphp
                                <form action="{{ route('admin.registrations.status', $registration) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="pending">
                                    <button type="submit" class="btn btn-sm btn-outline">Réactiver</button>
                                </form>
                                <button type="button" class="btn btn-sm btn-outline-light ms-1" title="Voir les détails" onclick="openRegistrationModal(JSON.parse(this.getAttribute('data-modal')))" data-modal="{{ $modalData }}">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center;color:var(--text-3);padding:3rem 1rem;">
                                Aucun historique d'annulation.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function switchTab(tab) {
    const active   = document.getElementById('tab-active');
    const canceled = document.getElementById('tab-canceled');
    const btnA     = document.getElementById('tab-btn-active');
    const btnC     = document.getElementById('tab-btn-canceled');

    if (tab === 'active') {
        active.style.display   = '';
        canceled.style.display = 'none';
        btnA.style.color       = 'var(--primary)';
        btnA.style.borderBottomColor = 'var(--primary)';
        btnC.style.color       = 'var(--text-2)';
        btnC.style.borderBottomColor = 'transparent';
    } else {
        active.style.display   = 'none';
        canceled.style.display = '';
        btnC.style.color       = 'var(--primary)';
        btnC.style.borderBottomColor = 'var(--primary)';
        btnA.style.color       = 'var(--text-2)';
        btnA.style.borderBottomColor = 'transparent';
    }
}
</script>
@endsection