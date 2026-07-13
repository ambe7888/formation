<?php $__env->startSection('title', 'Gestion des Paiements'); ?>

<?php $__env->startSection('content'); ?>
<div style="display:grid;grid-template-columns:1fr 320px;gap:1.5rem;align-items:start;">

    
    <div class="card p-4">
        <h5 style="margin-bottom:1.25rem;">Historique des transactions</h5>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Client / Formation</th>
                        <th>Montant</th>
                        <th>Méthode</th>
                        <th>Référence</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <?php if($payment->registration): ?>
                                    <strong><?php echo e(optional($payment->registration->client)->name ?? 'N/A'); ?></strong>
                                    <div style="font-size:0.78rem;color:var(--text-3);margin-top:2px;">
                                        <?php if($payment->registration->bundle_id): ?>
                                            Pack : <?php echo e(optional($payment->registration->bundle)->name ?? 'N/A'); ?>

                                        <?php else: ?>
                                            <?php echo e(optional($payment->registration->training)->title ?? 'N/A'); ?>

                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <span style="color:var(--text-3);font-style:italic;">Inscription orpheline</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong style="color:var(--text-1);">
                                    <?php echo e(number_format($payment->amount, 0, ',', ' ')); ?> <?php echo e($payment->currency ?? 'CFA'); ?>

                                </strong>
                            </td>
                            <td>
                                <span class="badge badge-muted"><?php echo e($payment->method ?? 'Non précisé'); ?></span>
                            </td>
                            <td>
                                <code style="font-family:'Fira Code',monospace;font-size:0.78rem;color:var(--text-3);">
                                    <?php echo e($payment->reference ?? '-'); ?>

                                </code>
                            </td>
                            <td>
                                <?php if($payment->status === 'completed'): ?>
                                    <span class="badge badge-success">Payé</span>
                                <?php elseif($payment->status === 'failed'): ?>
                                    <span class="badge badge-danger">Échoué</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">En attente</span>
                                <?php endif; ?>
                            </td>
                            <td style="font-size:0.78rem;color:var(--text-3);">
                                <?php echo e($payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : '-'); ?>

                            </td>
                            <td>
                                <?php if($payment->status === 'pending'): ?>
                                    <div style="display: flex; gap: 0.25rem;">
                                        <form action="<?php echo e(route('admin.payments.status', $payment->id)); ?>" method="POST" style="margin:0;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="btn btn-sm btn-success" title="Approuver" style="padding: 0.2rem 0.4rem;">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                                            </button>
                                        </form>
                                        <form action="<?php echo e(route('admin.payments.status', $payment->id)); ?>" method="POST" style="margin:0;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <input type="hidden" name="status" value="failed">
                                            <button type="submit" class="btn btn-sm btn-danger" title="Rejeter" style="padding: 0.2rem 0.4rem;">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" style="text-align:center;color:var(--text-3);padding:3rem 1rem;">
                                Aucun paiement enregistré pour le moment.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="card p-4">
        <h5 style="margin-bottom:1.25rem;">Enregistrer un paiement</h5>

        <?php if($registrations->isEmpty()): ?>
            <div class="alert alert-warning" style="font-size:0.85rem;">
                Aucune inscription disponible pour lier un paiement.
            </div>
        <?php else: ?>
            <form action="<?php echo e(route('admin.payments.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="mb-3">
                    <label for="registration_id" class="form-label">Inscription correspondante *</label>
                    <select class="form-select" id="registration_id" name="registration_id" required>
                        <option value="">Choisir une inscription</option>
                        <?php $__currentLoopData = $registrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($reg->id); ?>" data-amount="<?php echo e($reg->amount); ?>">
                                #<?php echo e($reg->id); ?> — <?php echo e(optional($reg->client)->name); ?>

                                (<?php echo e(number_format($reg->amount, 0, ',', ' ')); ?> CFA)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="amount" class="form-label">Montant (CFA) *</label>
                    <input type="number" class="form-control" id="amount" name="amount" required min="0" placeholder="ex : 50000">
                </div>

                <div class="mb-3">
                    <label for="method" class="form-label">Méthode de paiement *</label>
                    <select class="form-select" id="method" name="method" required>
                        <option value="Orange Money">Orange Money</option>
                        <option value="Wave">Wave</option>
                        <option value="MTN Mobile Money">MTN MoMo</option>
                        <option value="Moov Money">Moov Flooz</option>
                        <option value="Virement bancaire">Virement bancaire</option>
                        <option value="Espèces">Espèces</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Statut *</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="completed">Confirmé / Payé</option>
                        <option value="pending">En attente</option>
                        <option value="failed">Échoué</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="reference" class="form-label">Référence / ID transaction</label>
                    <input type="text" class="form-control" id="reference" name="reference" placeholder="ex : OM_897126381">
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
                    Enregistrer
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const regSelect   = document.getElementById('registration_id');
    const amountInput = document.getElementById('amount');
    if (regSelect && amountInput) {
        regSelect.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            const amt = opt.getAttribute('data-amount');
            if (amt) amountInput.value = amt;
        });
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\formation\resources\views/admin/payments/index.blade.php ENDPATH**/ ?>