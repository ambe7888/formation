

<?php $__env->startSection('title', 'Catégories'); ?>

<?php $__env->startSection('content'); ?>
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <a href="<?php echo e(route('admin.categories.create')); ?>" class="btn btn-success">Ajouter une catégorie</a>
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
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($category->name); ?></td>
                        <td><?php echo e($category->slug); ?></td>
                        <td><?php echo e(Str::limit($category->description, 60)); ?></td>
                        <td><?php echo e($category->sort_order); ?></td>
                        <td><?php echo e($category->trainings_count); ?></td>
                        <td class="text-end table-actions">
                            <form action="<?php echo e(route('admin.categories.move-up', $category)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button type="submit" class="btn btn-sm btn-secondary" <?php echo e($loop->first ? 'disabled' : ''); ?>>↑</button>
                            </form>
                            <form action="<?php echo e(route('admin.categories.move-down', $category)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button type="submit" class="btn btn-sm btn-secondary" <?php echo e($loop->last ? 'disabled' : ''); ?>>↓</button>
                            </form>
                            <a href="<?php echo e(route('admin.categories.edit', $category)); ?>" class="btn btn-sm btn-primary">Modifier</a>
                            <form action="<?php echo e(route('admin.categories.destroy', $category)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette catégorie ?');">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\formation\resources\views/admin/categories/index.blade.php ENDPATH**/ ?>