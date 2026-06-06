

<?php $__env->startSection('title', 'Ajouter une formation'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card card-borderless p-4">
        <?php if($categories->isEmpty()): ?>
            <div class="alert alert-warning">
                Aucune catégorie n'est définie. <a href="<?php echo e(route('admin.categories.create')); ?>">Ajoutez une catégorie</a> avant de créer une formation.
            </div>
        <?php endif; ?>
        <form action="<?php echo e(route('admin.trainings.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="title" class="form-label">Titre</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo e(old('title')); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="category_id" class="form-label">Catégorie</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">Choisir une catégorie</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id') == $category->id ? 'selected' : ''); ?>><?php echo e($category->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-12">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required><?php echo e(old('description')); ?></textarea>
                </div>
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Date de début</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo e(old('start_date')); ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="planned_month" class="form-label">Mois prévu</label>
                    <select class="form-select" id="planned_month" name="planned_month">
                        <option value="">Sélectionner un mois</option>
                        <option value="Juin" <?php echo e(old('planned_month') == 'Juin' ? 'selected' : ''); ?>>Juin</option>
                        <option value="Juillet" <?php echo e(old('planned_month') == 'Juillet' ? 'selected' : ''); ?>>Juillet</option>
                        <option value="Août" <?php echo e(old('planned_month') == 'Août' ? 'selected' : ''); ?>>Août</option>
                        <option value="Septembre" <?php echo e(old('planned_month') == 'Septembre' ? 'selected' : ''); ?>>Septembre</option>
                        <option value="Octobre" <?php echo e(old('planned_month') == 'Octobre' ? 'selected' : ''); ?>>Octobre</option>
                        <option value="Novembre" <?php echo e(old('planned_month') == 'Novembre' ? 'selected' : ''); ?>>Novembre</option>
                        <option value="Décembre" <?php echo e(old('planned_month') == 'Décembre' ? 'selected' : ''); ?>>Décembre</option>
                        <option value="Janvier" <?php echo e(old('planned_month') == 'Janvier' ? 'selected' : ''); ?>>Janvier</option>
                        <option value="Février" <?php echo e(old('planned_month') == 'Février' ? 'selected' : ''); ?>>Février</option>
                        <option value="Mars" <?php echo e(old('planned_month') == 'Mars' ? 'selected' : ''); ?>>Mars</option>
                        <option value="Avril" <?php echo e(old('planned_month') == 'Avril' ? 'selected' : ''); ?>>Avril</option>
                        <option value="Mai" <?php echo e(old('planned_month') == 'Mai' ? 'selected' : ''); ?>>Mai</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="location" class="form-label">Lieu</label>
                    <input type="text" class="form-control" id="location" name="location" value="<?php echo e(old('location')); ?>">
                </div>
                <div class="col-md-3">
                    <label for="seats" class="form-label">Nombre de places</label>
                    <input type="number" class="form-control" id="seats" name="seats" value="<?php echo e(old('seats')); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="price" class="form-label">Prix</label>
                    <input type="number" class="form-control" id="price" name="price" value="<?php echo e(old('price')); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="promo_price" class="form-label">Prix promo</label>
                    <input type="number" class="form-control" id="promo_price" name="promo_price" value="<?php echo e(old('promo_price')); ?>">
                </div>
                <div class="col-12">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>
                <div class="col-12">
                    <label class="form-label d-block mb-2">Compétences acquises</label>
                    <div class="d-flex flex-wrap gap-3 p-3 border rounded" style="background: #fafafa; max-height: 150px; overflow-y: auto;">
                        <?php $__empty_1 = true; $__currentLoopData = $skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="skills[]" id="skill_<?php echo e($skill->id); ?>" value="<?php echo e($skill->id); ?>" <?php echo e(is_array(old('skills')) && in_array($skill->id, old('skills')) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="skill_<?php echo e($skill->id); ?>">
                                    <span class="badge" style="background-color: <?php echo e($skill->badge_color); ?>; color: #fff;"><?php echo e($skill->name); ?></span>
                                </label>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <span class="text-muted small">Aucune compétence enregistrée. <a href="<?php echo e(route('admin.skills')); ?>" target="_blank">Gérer les compétences</a></span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Dynamic Resources Section -->
                <div class="col-12 mt-4">
                    <label class="form-label d-block mb-2"><strong>Supports de cours & Ressources d'apprentissage (débloqués après paiement)</strong></label>
                    <div id="resources-container" class="p-3 border rounded" style="background: #f8fafc;">
                        <div class="resource-row row g-2 mb-2 align-items-end">
                            <div class="col-md-4">
                                <label class="small text-muted mb-1">Titre de la ressource</label>
                                <input type="text" name="resource_title[]" class="form-control form-control-sm" placeholder="ex: Manuel PDF Module 1">
                            </div>
                            <div class="col-md-2">
                                <label class="small text-muted mb-1">Type</label>
                                <select name="resource_type[]" class="form-select form-select-sm">
                                    <option value="link">Lien externe</option>
                                    <option value="file">Fichier / PDF</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="small text-muted mb-1">Lien / URL</label>
                                <input type="text" name="resource_url[]" class="form-control form-control-sm" placeholder="ex: https://lien-telechargement.com/support.pdf">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="removeResourceRow(this)">Supprimer</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addResourceRow()">➕ Ajouter une ressource</button>
                </div>

                <script>
                    function addResourceRow() {
                        const container = document.getElementById('resources-container');
                        const row = document.createElement('div');
                        row.className = 'resource-row row g-2 mb-2 align-items-end';
                        row.innerHTML = `
                            <div class="col-md-4">
                                <input type="text" name="resource_title[]" class="form-control form-control-sm" placeholder="ex: Manuel PDF Module 1">
                            </div>
                            <div class="col-md-2">
                                <select name="resource_type[]" class="form-select form-select-sm">
                                    <option value="link">Lien externe</option>
                                    <option value="file">Fichier / PDF</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="resource_url[]" class="form-control form-control-sm" placeholder="ex: https://lien-telechargement.com/support.pdf">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="removeResourceRow(this)">Supprimer</button>
                            </div>
                        `;
                        container.appendChild(row);
                    }

                    function removeResourceRow(button) {
                        const row = button.closest('.resource-row');
                        if (row) {
                            row.remove();
                        }
                    }
                </script>

                <div class="col-md-4">
                    <label for="hero_order" class="form-label">Position du slide</label>
                    <input type="number" class="form-control" id="hero_order" name="hero_order" value="<?php echo e(old('hero_order')); ?>" min="0">
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" <?php echo e(old('is_active') ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="is_active">Publier sur la page d'accueil</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" <?php echo e(old('is_featured') ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="is_featured">Afficher dans le slider hero</label>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Créer</button>
                <a href="<?php echo e(route('admin.trainings')); ?>" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\formation\resources\views/admin/trainings/create.blade.php ENDPATH**/ ?>