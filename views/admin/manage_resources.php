<?php
/**
 * @var string $message
 * @var string $msgType
 * @var array $resources
 * @var array $categories
 * @var string $search
 * @var string $category
 * @var string $type
 * @var string $status
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Resources – LRIS | Olivarez College</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Shared Admin CSS -->
    <link rel="stylesheet" href="/library_system/assets/images/css/admin_dashboard.css">
    <link rel="stylesheet" href="/library_system/assets/images/css/admin_leftsidebar.css">
    <link rel="stylesheet" href="/library_system/assets/images/css/admin_topbar.css">
    <!-- Page-specific CSS -->
    <link rel="stylesheet" href="/library_system/assets/images/css/manage_resources.css">
</head>
<body>

<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<?php include __DIR__ . '/adminleft_sidebar.php'; ?>

<div class="main-wrapper">

    <?php include __DIR__ . '/admin_topbar.php'; ?>

    <!-- PAGE CONTENT -->
    <main class="page-content">

        <!-- Alert Message -->
        <?php if (!empty($message)): ?>
        <div class="alert-oc <?= $msgType ?>">
            <i class="bi bi-<?= $msgType === 'success' ? 'check-circle-fill' : 'exclamation-circle-fill' ?>"></i>
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php endif; ?>

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="section-title mb-0">All Learning Resources
                <span style="font-size:0.8rem;font-weight:500;color:var(--text-muted-oc);margin-left:8px;">
                    (<?= count($resources) ?> record<?= count($resources) !== 1 ? 's' : '' ?>)
                </span>
            </div>
            <button class="btn-oc-gold" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-lg me-1"></i> Add Resource
            </button>
        </div>

        <!-- Filter Bar -->
        <form method="GET" action="/library_system/index.php" class="filter-bar">
            <input type="hidden" name="action" value="admin_manage_resources">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="🔍  Search title, author, subject..."
                        value="<?= htmlspecialchars($search ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="col-6 col-md-2">
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="book"     <?= ($type ?? '') === 'book'     ? 'selected' : '' ?>>Book</option>
                        <option value="module"   <?= ($type ?? '') === 'module'   ? 'selected' : '' ?>>Module</option>
                        <option value="digital"  <?= ($type ?? '') === 'digital'  ? 'selected' : '' ?>>Digital File</option>
                        <option value="journal"  <?= ($type ?? '') === 'journal'  ? 'selected' : '' ?>>Journal</option>
                        <option value="thesis"   <?= ($type ?? '') === 'thesis'   ? 'selected' : '' ?>>Thesis</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="available"   <?= ($status ?? '') === 'available'   ? 'selected' : '' ?>>Available</option>
                        <option value="borrowed"    <?= ($status ?? '') === 'borrowed'    ? 'selected' : '' ?>>Borrowed</option>
                        <option value="unavailable" <?= ($status ?? '') === 'unavailable' ? 'selected' : '' ?>>Unavailable</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        <?php foreach ($categories ?? [] as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['category'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            <?= ($category ?? '') === ($cat['category'] ?? '') ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['category'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-6 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn-oc-primary flex-fill">Filter</button>
                    <a href="/library_system/index.php?action=admin_manage_resources" class="btn btn-outline-secondary" style="border-radius:10px;font-size:0.85rem;padding:9px 12px;">Clear</a>
                </div>
            </div>
        </form>

        <!-- Resources Table -->
        <div class="content-card">
            <div class="table-responsive">
                <table class="table-clean">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Subject / Category</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Date Added</th>
                            <th style="text-align:center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($resources)): ?>
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="bi bi-journal-x"></i>
                                    <p>No resources found. Click <strong>Add Resource</strong> to get started.</p>
                                </div>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($resources as $i => $r): ?>
                        <tr>
                            <td style="color:var(--text-muted-oc); font-size:0.8rem;"><?= $i + 1 ?></td>
                            <td>
                                <div style="font-weight:600; color:#1a202c; font-size:0.87rem;"><?= htmlspecialchars($r['title'], ENT_QUOTES, 'UTF-8') ?></div>
                                <?php if (!empty($r['description'])): ?>
                                <div style="font-size:0.75rem;color:var(--text-muted-oc);margin-top:2px; max-width:220px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                    <?= htmlspecialchars($r['description'], ENT_QUOTES, 'UTF-8') ?>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($r['author'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <?php if (!empty($r['subject'])): ?>
                                <div style="font-size:0.85rem;"><?= htmlspecialchars($r['subject'], ENT_QUOTES, 'UTF-8') ?></div>
                                <?php endif; ?>
                                <?php if (!empty($r['category'])): ?>
                                <span style="font-size:0.72rem;color:var(--text-muted-oc);"><?= htmlspecialchars($r['category'], ENT_QUOTES, 'UTF-8') ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $typeIcons = ['book'=>'bi-book','module'=>'bi-file-text','digital'=>'bi-file-earmark-pdf','journal'=>'bi-journal-text','thesis'=>'bi-mortarboard'];
                                $icon = $typeIcons[$r['type']] ?? 'bi-file';
                                ?>
                                <span style="font-size:0.82rem;"><i class="bi <?= $icon ?> me-1"></i><?= ucfirst(htmlspecialchars($r['type'], ENT_QUOTES, 'UTF-8')) ?></span>
                            </td>
                            <td>
                                <?php
                                $badgeClass = match($r['status']) {
                                    'available'   => 'badge-available',
                                    'borrowed'    => 'badge-borrowed',
                                    default       => 'badge-unavailable'
                                };
                                ?>
                                <span class="badge-oc <?= $badgeClass ?>"><?= ucfirst($r['status']) ?></span>
                            </td>
                            <td style="font-size:0.82rem; color:var(--text-muted-oc);">
                                <?= date('M d, Y', strtotime($r['created_at'])) ?>
                            </td>
                            <td style="text-align:center;">
                                <div class="d-flex gap-1 justify-content-center">
                                    <!-- Edit Button -->
                                    <button class="action-btn edit"
                                        onclick="openEditModal(<?= htmlspecialchars(json_encode($r), ENT_QUOTES, 'UTF-8') ?>)">
                                        <i class="bi bi-pencil-fill"></i> Edit
                                    </button>
                                    <!-- Delete Button -->
                                    <button class="action-btn delete"
                                        onclick="openDeleteModal(<?= $r['id'] ?>, '<?= addslashes(htmlspecialchars($r['title'], ENT_QUOTES, 'UTF-8')) ?>')">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>

<?php require_once __DIR__ . '/resource_modals.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Sidebar toggle
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('sidebarOverlay').classList.toggle('show');
    }

    // Open Edit Modal & populate fields
    function openEditModal(resource) {
        document.getElementById('edit_id').value          = resource.id;
        document.getElementById('edit_title').value       = resource.title;
        document.getElementById('edit_author').value      = resource.author;
        document.getElementById('edit_subject').value     = resource.subject ?? '';
        document.getElementById('edit_category').value   = resource.category ?? '';
        document.getElementById('edit_description').value= resource.description ?? '';
        document.getElementById('edit_file_path').value  = resource.file_path ?? '';
        document.getElementById('edit_type').value        = resource.type;
        document.getElementById('edit_status').value      = resource.status;
        new bootstrap.Modal(document.getElementById('editModal')).show();
    }

    // Open Delete Confirm Modal
    function openDeleteModal(id, title) {
        document.getElementById('delete_id').value              = id;
        document.getElementById('delete_title_display').textContent = title;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }

    // Auto-dismiss alert after 4 seconds
    const alert = document.querySelector('.alert-oc');
    if (alert) setTimeout(() => alert.style.display = 'none', 4000);
</script>
</body>
</html>
