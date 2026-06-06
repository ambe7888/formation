

<?php $__env->startSection('title', 'Modifier une formation'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card card-borderless p-4">
        <form action="<?php echo e(route('admin.trainings.update', $training)); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Titre</label>
                    <input type="text" name="title" value="<?php echo e(old('title', $training->title)); ?>" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Catégorie</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">Choisir une catégorie</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id', $training->category_id) == $category->id ? 'selected' : ''); ?>><?php echo e($category->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4" required><?php echo e(old('description', $training->description)); ?></textarea>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date de début</label>
                    <input type="date" name="start_date" value="<?php echo e(old('start_date', $training->start_date->format('Y-m-d'))); ?>" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Mois prévu</label>
                    <select name="planned_month" class="form-select">
                        <option value="">Sélectionner un mois</option>
                        <option value="Juin" <?php echo e(old('planned_month', $training->planned_month) == 'Juin' ? 'selected' : ''); ?>>Juin</option>
                        <option value="Juillet" <?php echo e(old('planned_month', $training->planned_month) == 'Juillet' ? 'selected' : ''); ?>>Juillet</option>
                        <option value="Août" <?php echo e(old('planned_month', $training->planned_month) == 'Août' ? 'selected' : ''); ?>>Août</option>
                        <option value="Septembre" <?php echo e(old('planned_month', $training->planned_month) == 'Septembre' ? 'selected' : ''); ?>>Septembre</option>
                        <option value="Octobre" <?php echo e(old('planned_month', $training->planned_month) == 'Octobre' ? 'selected' : ''); ?>>Octobre</option>
                        <option value="Novembre" <?php echo e(old('planned_month', $training->planned_month) == 'Novembre' ? 'selected' : ''); ?>>Novembre</option>
                        <option value="Décembre" <?php echo e(old('planned_month', $training->planned_month) == 'Décembre' ? 'selected' : ''); ?>>Décembre</option>
                        <option value="Janvier" <?php echo e(old('planned_month', $training->planned_month) == 'Janvier' ? 'selected' : ''); ?>>Janvier</option>
                        <option value="Février" <?php echo e(old('planned_month', $training->planned_month) == 'Février' ? 'selected' : ''); ?>>Février</option>
                        <option value="Mars" <?php echo e(old('planned_month', $training->planned_month) == 'Mars' ? 'selected' : ''); ?>>Mars</option>
                        <option value="Avril" <?php echo e(old('planned_month', $training->planned_month) == 'Avril' ? 'selected' : ''); ?>>Avril</option>
                        <option value="Mai" <?php echo e(old('planned_month', $training->planned_month) == 'Mai' ? 'selected' : ''); ?>>Mai</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Lieu</label>
                    <input type="text" name="location" value="<?php echo e(old('location', $training->location)); ?>" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Places</label>
                    <input type="number" name="seats" value="<?php echo e(old('seats', $training->seats)); ?>" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Prix</label>
                    <input type="number" name="price" value="<?php echo e(old('price', $training->price)); ?>" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Prix promo</label>
                    <input type="number" name="promo_price" value="<?php echo e(old('promo_price', $training->promo_price)); ?>" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label">Image</label>
                    <input type="file" name="image" class="form-control">
                    <?php if($training->image_url): ?>
                        <small class="text-muted">Image actuelle : <?php echo e($training->image_url); ?></small>
                    <?php endif; ?>
                </div>
                <div class="col-12">
                    <label class="form-label d-block mb-2">Compétences acquises</label>
                    <div class="d-flex flex-wrap gap-3 p-3 border rounded" style="background: #fafafa; max-height: 150px; overflow-y: auto;">
                        <?php $__empty_1 = true; $__currentLoopData = $skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="skills[]" id="skill_<?php echo e($skill->id); ?>" value="<?php echo e($skill->id); ?>" <?php echo e(is_array(old('skills')) ? (in_array($skill->id, old('skills')) ? 'checked' : '') : ($training->skills->contains($skill->id) ? 'checked' : '')); ?>>
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
                        <?php $__empty_1 = true; $__currentLoopData = $training->resources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resource): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="resource-row row g-2 mb-2 align-items-end">
                                <div class="col-md-4">
                                    <label class="small text-muted mb-1">Titre de la ressource</label>
                                    <input type="text" name="resource_title[]" value="<?php echo e($resource->title); ?>" class="form-control form-control-sm" placeholder="ex: Manuel PDF Module 1">
                                </div>
                                <div class="col-md-2">
                                    <label class="small text-muted mb-1">Type</label>
                                    <select name="resource_type[]" class="form-select form-select-sm">
                                        <option value="link" <?php echo e($resource->type === 'link' ? 'selected' : ''); ?>>Lien externe</option>
                                        <option value="file" <?php echo e($resource->type === 'file' ? 'selected' : ''); ?>>Fichier / PDF</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="small text-muted mb-1">Lien / URL</label>
                                    <input type="text" name="resource_url[]" value="<?php echo e($resource->url); ?>" class="form-control form-control-sm" placeholder="ex: https://lien-telechargement.com/support.pdf">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="removeResourceRow(this)">Supprimer</button>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
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
                        <?php endif; ?>
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
                    <label class="form-label">Position slider</label>
                    <input type="number" name="hero_order" value="<?php echo e(old('hero_order', $training->hero_order)); ?>" class="form-control" min="0">
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" <?php echo e(old('is_active', $training->is_active) ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="is_active">Publier sur la page d'accueil</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" <?php echo e(old('is_featured', $training->is_featured) ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="is_featured">Afficher dans le slider hero</label>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                <a href="<?php echo e(route('admin.trainings')); ?>" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\formation\resources\views/admin/trainings/edit.blade.php ENDPATH**/ ?>