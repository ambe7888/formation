<?php $__env->startSection('title', 'Gestion des Inscriptions'); ?>

<?php $__env->startSection('content'); ?>


<div style="display:flex;gap:0.5rem;margin-bottom:1.5rem;border-bottom:2px solid var(--border);padding-bottom:0;">
    <button id="tab-btn-active" onclick="switchTab('active')"
        style="padding:0.6rem 1.25rem;border:none;background:none;font-family:'Fira Sans',sans-serif;font-size:0.9rem;font-weight:600;cursor:pointer;color:var(--primary);border-bottom:2px solid var(--primary);margin-bottom:-2px;transition:all 150ms;">
        Inscriptions actives
        <span style="display:inline-flex;align-items:center;padding:0.1rem 0.5rem;border-radius:999px;font-size:0.7rem;background:var(--primary-dim);color:var(--primary);margin-left:0.4rem;"><?php echo e($activeRegistrations->count()); ?></span>
    </button>
    <button id="tab-btn-canceled" onclick="switchTab('canceled')"
        style="padding:0.6rem 1.25rem;border:none;background:none;font-family:'Fira Sans',sans-serif;font-size:0.9rem;font-weight:600;cursor:pointer;color:var(--text-2);border-bottom:2px solid transparent;margin-bottom:-2px;transition:all 150ms;">
        Historique & Annulations
        <span style="display:inline-flex;align-items:center;padding:0.1rem 0.5rem;border-radius:999px;font-size:0.7rem;background:var(--bg-hover);color:var(--text-3);margin-left:0.4rem;"><?php echo e($canceledRegistrations->count()); ?></span>
    </button>
</div>


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
                    <?php $__empty_1 = true; $__currentLoopData = $activeRegistrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registration): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            
                            <td>
                                <strong><?php echo e(optional($registration->client)->name ?? 'N/A'); ?></strong>
                                <div style="font-size:0.75rem;color:var(--text-3);margin-top:2px;"><?php echo e(optional($registration->client)->phone ?? ''); ?></div>
                                <div style="font-size:0.75rem;color:var(--text-3);"><?php echo e(optional($registration->client)->email ?? ''); ?></div>
                            </td>

                            
                            <td>
                                <?php if($registration->bundle_id): ?>
                                    <span style="display:inline-flex;align-items:center;padding:0.25rem 0.65rem;border-radius:8px;font-size:0.8rem;font-weight:600;background:rgba(139,92,246,0.1);color:#8B5CF6;border:1px solid rgba(139,92,246,0.2);">
                                        Pack : <?php echo e(optional($registration->bundle)->name ?? 'N/A'); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-muted" style="font-size:0.8rem;">
                                        <?php echo e(optional($registration->training)->title ?? 'N/A'); ?>

                                    </span>
                                <?php endif; ?>
                            </td>

                            
                            <td>
                                <?php $notes = json_decode($registration->notes ?? '[]', true) ?: []; ?>
                                <?php if(!empty($notes)): ?>
                                    <div style="font-size:0.78rem;color:var(--text-2);">
                                        <?php if(isset($notes['month'])): ?>
                                            <div><strong style="color:var(--text-1);">Mois :</strong> <?php echo e($notes['month']); ?></div>
                                        <?php endif; ?>
                                        <?php if(!empty($notes['message'])): ?>
                                            <div style="color:var(--text-3);font-style:italic;max-width:200px;white-space:normal;margin-top:2px;">"<?php echo e(Str::limit($notes['message'], 60)); ?>"</div>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <span style="color:var(--text-3);">—</span>
                                <?php endif; ?>
                            </td>

                            
                            <td style="font-size:0.8rem;">
                                <div><span style="color:var(--text-3);">Total :</span> <strong><?php echo e(number_format($registration->amount, 0, ',', ' ')); ?></strong></div>
                                <div><span style="color:var(--accent);">Versé :</span> <strong><?php echo e(number_format($registration->amount_paid, 0, ',', ' ')); ?></strong></div>
                                <div>
                                    <span style="color:var(--text-3);">Reste :</span>
                                    <strong style="color:<?php echo e($registration->balance_due > 0 ? 'var(--danger)' : 'var(--text-3)'); ?>;">
                                        <?php echo e(number_format($registration->balance_due, 0, ',', ' ')); ?>

                                    </strong>
                                </div>
                            </td>

                            
                            <td>
                                <?php $ps = $registration->payment_status; ?>
                                <?php if($ps === 'paid'): ?>
                                    <span class="badge badge-success">Payé</span>
                                <?php elseif($ps === 'partial'): ?>
                                    <span class="badge badge-warning">Partiel</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Non payé</span>
                                <?php endif; ?>
                            </td>

                            
                            <td>
                                <form action="<?php echo e(route('admin.registrations.status', $registration)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <select name="status" class="form-select" onchange="this.form.submit()"
                                        style="font-size:0.8rem;padding:0.3rem 0.6rem;width:auto;min-width:130px;">
                                        <option value="pending"   <?php echo e($registration->status === 'pending'   ? 'selected' : ''); ?>>En attente</option>
                                        <option value="confirmed" <?php echo e($registration->status === 'confirmed' ? 'selected' : ''); ?>>Confirmée</option>
                                        <option value="canceled"  <?php echo e($registration->status === 'canceled'  ? 'selected' : ''); ?>>Annuler</option>
                                    </select>
                                </form>
                            </td>

                            
                            <td style="font-size:0.75rem;color:var(--text-3);white-space:nowrap;">
                                <?php echo e(optional($registration->created_at)->format('d/m/Y H:i')); ?>

                            </td>

                            
                            <?php
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
                            ?>
                            <td class="text-end table-actions">
                                <button type="button" class="btn btn-sm btn-outline-light" title="Voir les détails" onclick="openRegistrationModal(JSON.parse(this.getAttribute('data-modal')))" data-modal="<?php echo e($modalData); ?>">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" style="text-align:center;color:var(--text-3);padding:3rem 1rem;">
                                Aucune inscription active.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


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
                    <?php $__empty_1 = true; $__currentLoopData = $canceledRegistrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registration): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <strong><?php echo e(optional($registration->client)->name ?? 'N/A'); ?></strong>
                                <div style="font-size:0.75rem;color:var(--text-3);"><?php echo e(optional($registration->client)->email ?? ''); ?></div>
                            </td>
                            <td>
                                <?php if($registration->bundle_id): ?>
                                    <span style="display:inline-flex;padding:0.25rem 0.65rem;border-radius:8px;font-size:0.8rem;font-weight:600;background:rgba(139,92,246,0.1);color:#8B5CF6;border:1px solid rgba(139,92,246,0.2);">
                                        Pack : <?php echo e(optional($registration->bundle)->name ?? 'N/A'); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-muted" style="font-size:0.8rem;">
                                        <?php echo e(optional($registration->training)->title ?? 'N/A'); ?>

                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php $notes = json_decode($registration->notes ?? '[]', true) ?: []; ?>
                                <div style="font-size:0.78rem;">
                                    <div style="font-weight:600;color:var(--badge-danger-text);">
                                        Annulée le : <?php echo e(isset($notes['canceled_at']) ? \Carbon\Carbon::parse($notes['canceled_at'])->format('d/m/Y H:i') : optional($registration->updated_at)->format('d/m/Y H:i')); ?>

                                    </div>
                                    <div style="color:var(--text-3);margin-top:2px;">
                                        Par :
                                        <?php if(!empty($notes['canceled_by_student'])): ?>
                                            Étudiant
                                        <?php else: ?>
                                            Administrateur
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td style="font-size:0.78rem;color:var(--text-3);">
                                <div>Total : <?php echo e(number_format($registration->amount, 0, ',', ' ')); ?></div>
                                <div>Versé : <?php echo e(number_format($registration->amount_paid, 0, ',', ' ')); ?></div>
                            </td>
                            <td><span class="badge badge-danger">Annulée</span></td>
                            <td style="font-size:0.75rem;color:var(--text-3);white-space:nowrap;">
                                <?php echo e(optional($registration->created_at)->format('d/m/Y H:i')); ?>

                            </td>
                            <td>
                                <?php
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
                                ?>
                                <form action="<?php echo e(route('admin.registrations.status', $registration)); ?>" method="POST" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <input type="hidden" name="status" value="pending">
                                    <button type="submit" class="btn btn-sm btn-outline">Réactiver</button>
                                </form>
                                <button type="button" class="btn btn-sm btn-outline-light ms-1" title="Voir les détails" onclick="openRegistrationModal(JSON.parse(this.getAttribute('data-modal')))" data-modal="<?php echo e($modalData); ?>">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" style="text-align:center;color:var(--text-3);padding:3rem 1rem;">
                                Aucun historique d'annulation.
                            </td>
                        </tr>
                    <?php endif; ?>
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\formation\resources\views/admin/registrations/index.blade.php ENDPATH**/ ?>