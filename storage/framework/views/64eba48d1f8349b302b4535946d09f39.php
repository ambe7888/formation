<?php $__env->startSection('title', 'Gestion des Packs / Bundles'); ?>

<?php $__env->startSection('content'); ?>
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;">
    <p style="color:var(--text-2);font-size:0.875rem;margin:0;">Regroupez plusieurs formations pour proposer des offres groupées à prix réduit.</p>
    <a href="<?php echo e(route('admin.bundles.create')); ?>" class="btn btn-primary">
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
                <?php $__empty_1 = true; $__currentLoopData = $bundles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bundle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <strong><?php echo e($bundle->name); ?></strong>
                            <?php if($bundle->description): ?>
                                <small class="text-muted d-block"><?php echo e(Str::limit($bundle->description, 80)); ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge badge-primary" style="font-size:0.85rem;">
                                <?php echo e(number_format($bundle->price, 0, ',', ' ')); ?> CFA
                            </span>
                        </td>
                        <td>
                            <div style="display:flex;flex-wrap:wrap;gap:0.4rem;">
                                <?php $__currentLoopData = $bundle->trainings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $training): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="badge badge-muted"><?php echo e(Str::limit($training->title, 25)); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </td>
                        <td style="text-align:right;white-space:nowrap;" class="table-actions">
                            <a href="<?php echo e(route('admin.bundles.edit', $bundle)); ?>" class="btn btn-sm btn-outline">Modifier</a>
                            <form action="<?php echo e(route('admin.bundles.destroy', $bundle)); ?>" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer ce pack ?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-5">
                            Aucun pack promotionnel n'est défini pour le moment.<br>
                            <a href="<?php echo e(route('admin.bundles.create')); ?>" class="btn btn-sm btn-primary mt-3">Créer votre premier Pack</a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\formation\resources\views/admin/bundles/index.blade.php ENDPATH**/ ?>