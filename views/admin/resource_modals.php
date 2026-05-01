<?php
/**
 * @var array $categories
 */
?>

<!-- Datalist for Dynamic SQL Categories -->
<datalist id="sql_categories">
    <?php foreach ($categories ?? [] as $cat): ?>
        <?php if (!empty($cat['category'])): ?>
            <option value="<?= htmlspecialchars($cat['category'], ENT_QUOTES, 'UTF-8') ?>">
        <?php endif; ?>
    <?php endforeach; ?>
</datalist>

<!-- ══════════ ADD RESOURCE MODAL ══════════ -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-oc">
                <h5 class="modal-title fw-700"><i class="bi bi-plus-circle-fill me-2"></i>Add New Resource</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/library_system/index.php?action=admin_manage_resources">
                <input type="hidden" name="form_action" value="add">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="modal-label">Title <span style="color:#dc2626">*</span></label>
                            <input type="text" name="title" class="modal-input" placeholder="e.g. Introduction to Programming" required>
                        </div>
                        <div class="col-md-6">
                            <label class="modal-label">Author <span style="color:#dc2626">*</span></label>
                            <input type="text" name="author" class="modal-input" placeholder="e.g. Juan Dela Cruz" required>
                        </div>
                        <div class="col-md-6">
                            <label class="modal-label">Subject</label>
                            <input type="text" name="subject" class="modal-input" placeholder="e.g. Computer Science">
                        </div>
                        <div class="col-md-4">
                            <label class="modal-label">Category</label>
                            <!-- Connected to SQL Datalist -->
                            <input list="sql_categories" name="category" class="modal-input" placeholder="e.g. IT, Nursing, Education">
                        </div>
                        <div class="col-md-4">
                            <label class="modal-label">Type</label>
                            <select name="type" class="modal-input">
                                <option value="book">Book</option>
                                <option value="module">Module</option>
                                <option value="digital">Digital File</option>
                                <option value="journal">Journal</option>
                                <option value="thesis">Thesis</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="modal-label">Status</label>
                            <select name="status" class="modal-input">
                                <option value="available">Available</option>
                                <option value="borrowed">Borrowed</option>
                                <option value="unavailable">Unavailable</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="modal-label">Description</label>
                            <textarea name="description" class="modal-input" rows="3" placeholder="Brief description of the resource..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="modal-label">File Path / URL (for digital files)</label>
                            <input type="text" name="file_path" class="modal-input" placeholder="e.g. uploads/sample.pdf">
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-oc">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius:9px;">Cancel</button>
                    <button type="submit" class="btn-oc-primary"><i class="bi bi-plus-lg me-1"></i>Add Resource</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ══════════ EDIT RESOURCE MODAL ══════════ -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-oc">
                <h5 class="modal-title fw-700"><i class="bi bi-pencil-fill me-2"></i>Edit Resource</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/library_system/index.php?action=admin_manage_resources">
                <input type="hidden" name="form_action" value="edit">
                <input type="hidden" name="resource_id" id="edit_id">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="modal-label">Title <span style="color:#dc2626">*</span></label>
                            <input type="text" name="title" id="edit_title" class="modal-input" required>
                        </div>
                        <div class="col-md-6">
                            <label class="modal-label">Author <span style="color:#dc2626">*</span></label>
                            <input type="text" name="author" id="edit_author" class="modal-input" required>
                        </div>
                        <div class="col-md-6">
                            <label class="modal-label">Subject</label>
                            <input type="text" name="subject" id="edit_subject" class="modal-input">
                        </div>
                        <div class="col-md-4">
                            <label class="modal-label">Category</label>
                            <!-- Connected to SQL Datalist -->
                            <input list="sql_categories" name="category" id="edit_category" class="modal-input">
                        </div>
                        <div class="col-md-4">
                            <label class="modal-label">Type</label>
                            <select name="type" id="edit_type" class="modal-input">
                                <option value="book">Book</option>
                                <option value="module">Module</option>
                                <option value="digital">Digital File</option>
                                <option value="journal">Journal</option>
                                <option value="thesis">Thesis</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="modal-label">Status</label>
                            <select name="status" id="edit_status" class="modal-input">
                                <option value="available">Available</option>
                                <option value="borrowed">Borrowed</option>
                                <option value="unavailable">Unavailable</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="modal-label">Description</label>
                            <textarea name="description" id="edit_description" class="modal-input" rows="3"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="modal-label">File Path / URL</label>
                            <input type="text" name="file_path" id="edit_file_path" class="modal-input">
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-oc">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius:9px;">Cancel</button>
                    <button type="submit" class="btn-oc-primary"><i class="bi bi-check-lg me-1"></i>Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ══════════ DELETE CONFIRM MODAL ══════════ -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom:1px solid #f1f5f9; padding:18px 22px;">
                <h5 class="modal-title fw-700" style="color:#dc2626;"><i class="bi bi-exclamation-triangle-fill me-2"></i>Delete Resource</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p style="font-size:0.9rem; color:#334155;">Are you sure you want to delete:</p>
                <p id="delete_title_display" style="font-weight:700; font-size:1rem; color:#1a202c; margin-top:8px;"></p>
                <p style="font-size:0.82rem; color:#dc2626; margin-top:10px;"><i class="bi bi-exclamation-circle me-1"></i>This action cannot be undone.</p>
            </div>
            <form method="POST" action="/library_system/index.php?action=admin_manage_resources">
                <input type="hidden" name="form_action" value="delete">
                <input type="hidden" name="resource_id" id="delete_id">
                <div class="modal-footer modal-footer-oc">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius:9px;">Cancel</button>
                    <button type="submit" class="btn btn-danger" style="border-radius:9px; font-weight:600;">
                        <i class="bi bi-trash-fill me-1"></i>Yes, Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
