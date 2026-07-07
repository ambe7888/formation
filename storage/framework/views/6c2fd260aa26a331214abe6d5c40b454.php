<?php $__env->startSection('title', 'Formations'); ?>

<?php $__env->startSection('content'); ?>
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <a href="<?php echo e(route('admin.trainings.create')); ?>" class="btn btn-success">Ajouter une formation</a>
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
                <?php $__currentLoopData = $trainings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $training): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($training->title); ?></td>
                        <td><?php echo e($training->category?->name ?? $training->category); ?></td>
                        <td><?php echo e($training->start_date->format('d/m/Y')); ?></td>
                        <td><?php echo e(number_format($training->price, 0, ',', ' ')); ?> XOF</td>
                        <td><?php echo e($training->seats); ?></td>
                        <td>
                            <?php if($training->is_featured): ?>
                                <span class="badge bg-info text-dark">Hero</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="status-badge <?php echo e($training->is_active ? 'status-active' : 'status-inactive'); ?>">
                                <?php echo e($training->is_active ? 'Active' : 'Inactive'); ?>

                            </span>
                        </td>
                        <td class="text-end table-actions">
                            <a href="<?php echo e(route('admin.trainings.edit', $training)); ?>" class="btn btn-sm btn-primary">Modifier</a>
                            <form action="<?php echo e(route('admin.trainings.destroy', $training)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette formation ?');">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\formation\resources\views/admin/trainings/index.blade.php ENDPATH**/ ?>