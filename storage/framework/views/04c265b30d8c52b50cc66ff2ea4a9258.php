<?php $__env->startSection('title', 'Compétences'); ?>

<?php $__env->startSection('content'); ?>
<div style="display:grid;grid-template-columns:1fr 340px;gap:1.5rem;align-items:start;">

    
    <div class="card p-4">
        <h5 style="margin-bottom:1.25rem;">Liste des compétences</h5>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Slug</th>
                        <th>Aperçu Badge</th>
                        <th>Formations</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><strong><?php echo e($skill->name); ?></strong></td>
                            <td><code style="font-family:'Fira Code',monospace;font-size:0.8rem;color:var(--text-3);"><?php echo e($skill->slug); ?></code></td>
                            <td>
                                <span style="display:inline-flex;align-items:center;padding:0.25rem 0.7rem;border-radius:999px;font-size:0.78rem;font-weight:600;color:#fff;background-color:<?php echo e($skill->badge_color); ?>;">
                                    <?php echo e($skill->name); ?>

                                </span>
                            </td>
                            <td>
                                <span class="badge badge-muted"><?php echo e($skill->trainings_count); ?> formation(s)</span>
                            </td>
                            <td style="text-align:right;white-space:nowrap;" class="table-actions">
                                <button onclick="openEditModal(this)" 
                                        data-id="<?php echo e($skill->id); ?>"
                                        data-name="<?php echo e($skill->name); ?>"
                                        data-slug="<?php echo e($skill->slug); ?>"
                                        data-color="<?php echo e($skill->badge_color); ?>"
                                        class="btn btn-sm btn-outline">Modifier</button>
                                <form action="<?php echo e(route('admin.skills.destroy', $skill)); ?>" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cette compétence ?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" style="text-align:center;color:var(--text-3);padding:3rem 1rem;">
                                Aucune compétence enregistrée.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="card p-4">
        <h5 style="margin-bottom:1.25rem;">Ajouter une compétence</h5>
        <form action="<?php echo e(route('admin.skills.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label for="name" class="form-label">Titre *</label>
                <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <?php if(!session('open_edit_modal')): ?> is-invalid <?php endif; ?> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name" name="name" value="<?php echo e(old('name')); ?>" required placeholder="ex : Prompt Engineering">
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <?php if(!session('open_edit_modal')): ?>
                        <div class="invalid-feedback text-danger text-sm mt-1"><?php echo e($message); ?></div>
                    <?php endif; ?>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="mb-3">
                <label for="slug" class="form-label">Slug <span style="font-weight:400;color:var(--text-3);">(optionnel)</span></label>
                <input type="text" class="form-control <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <?php if(!session('open_edit_modal')): ?> is-invalid <?php endif; ?> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="slug" name="slug" value="<?php echo e(old('slug')); ?>" placeholder="ex : prompt-engineering">
                <div style="font-size:0.75rem;color:var(--text-3);margin-top:0.3rem;">Laissez vide pour générer automatiquement.</div>
                <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <?php if(!session('open_edit_modal')): ?>
                        <div class="invalid-feedback text-danger text-sm mt-1"><?php echo e($message); ?></div>
                    <?php endif; ?>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="mb-3">
                <label for="badge_color" class="form-label">Couleur du badge *</label>
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <input type="color" id="badge_color" name="badge_color" value="<?php echo e(old('badge_color', '#4f46e5')); ?>"
                           style="width:44px;height:36px;border-radius:8px;border:1px solid var(--border);background:var(--bg-surface);cursor:pointer;padding:2px;">
                    <span style="font-size:0.82rem;color:var(--text-3);">Cliquez pour choisir</span>
                </div>
                <?php $__errorArgs = ['badge_color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <?php if(!session('open_edit_modal')): ?>
                        <div class="invalid-feedback text-danger text-sm mt-1"><?php echo e($message); ?></div>
                    <?php endif; ?>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <button type="submit" class="btn btn-primary w-100" style="margin-top:0.5rem;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Créer la compétence
            </button>
        </form>
    </div>
</div>

<!-- ── MODAL: EDIT SKILL ── -->
<div class="modal-overlay" id="editSkillModal" onclick="closeEditModal(event)">
    <div class="modal-container" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 class="modal-title">Modifier la compétence</h3>
            <button class="modal-close" onclick="closeEditModal()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <div class="modal-body">
            <form id="edit_skill_form" action="" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="mb-3">
                    <label for="edit_name" class="form-label">Titre *</label>
                    <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <?php if(session('open_edit_modal')): ?> is-invalid <?php endif; ?> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="edit_name" name="name" value="<?php echo e(old('name')); ?>" required>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <?php if(session('open_edit_modal')): ?>
                            <div class="invalid-feedback text-danger text-sm mt-1"><?php echo e($message); ?></div>
                        <?php endif; ?>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-3">
                    <label for="edit_slug" class="form-label">Slug <span style="font-weight:400;color:var(--text-3);">(optionnel)</span></label>
                    <input type="text" class="form-control <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <?php if(session('open_edit_modal')): ?> is-invalid <?php endif; ?> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="edit_slug" name="slug" value="<?php echo e(old('slug')); ?>">
                    <div style="font-size:0.75rem;color:var(--text-3);margin-top:0.3rem;">Laissez vide pour générer automatiquement.</div>
                    <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <?php if(session('open_edit_modal')): ?>
                            <div class="invalid-feedback text-danger text-sm mt-1"><?php echo e($message); ?></div>
                        <?php endif; ?>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-3">
                    <label for="edit_badge_color" class="form-label">Couleur du badge *</label>
                    <div style="display:flex;align-items:center;gap:0.75rem;">
                        <input type="color" id="edit_badge_color" name="badge_color" value="<?php echo e(old('badge_color')); ?>"
                               style="width:44px;height:36px;border-radius:8px;border:1px solid var(--border);background:var(--bg-surface);cursor:pointer;padding:2px;">
                        <span style="font-size:0.82rem;color:var(--text-3);">Cliquez pour choisir</span>
                    </div>
                    <?php $__errorArgs = ['badge_color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <?php if(session('open_edit_modal')): ?>
                            <div class="invalid-feedback text-danger text-sm mt-1"><?php echo e($message); ?></div>
                        <?php endif; ?>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                    <button type="button" class="btn btn-secondary me-2" onclick="closeEditModal(event)">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const editModal = document.getElementById('editSkillModal');

    function openEditModal(btn) {
        const id = btn.getAttribute('data-id');
        const name = btn.getAttribute('data-name');
        const slug = btn.getAttribute('data-slug');
        const color = btn.getAttribute('data-color');

        // Set action url
        document.getElementById('edit_skill_form').action = `<?php echo e(url('/admin/skills')); ?>/${id}`;
        
        // Fill form fields
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_slug').value = slug === 'null' ? '' : (slug || '');
        document.getElementById('edit_badge_color').value = color;

        editModal.classList.add('active');
    }

    function closeEditModal(e) {
        if (e && e.target !== editModal && !e.target.closest('.modal-close') && !e.target.closest('.btn-secondary')) return;
        editModal.classList.remove('active');
    }

    <?php if(session('open_edit_modal')): ?>
        document.addEventListener('DOMContentLoaded', function() {
            const skillId = "<?php echo e(session('open_edit_modal')); ?>";
            const btn = document.querySelector(`button[data-id="${skillId}"]`);
            if (btn) {
                openEditModal(btn);
                // Override with user's old input values
                document.getElementById('edit_name').value = "<?php echo e(old('name')); ?>";
                document.getElementById('edit_slug').value = "<?php echo e(old('slug')); ?>";
                document.getElementById('edit_badge_color').value = "<?php echo e(old('badge_color')); ?>";
            }
        });
    <?php endif; ?>
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\formation\resources\views/admin/skills/index.blade.php ENDPATH**/ ?>