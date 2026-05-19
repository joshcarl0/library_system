<?php
/**
 * @var array $resources
 * @var array $categories
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard – LRIS | Olivarez College</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Student Specific CSS -->
    <link rel="stylesheet" href="/library_system/assets/images/css/student/student_sidebar.css">
    <link rel="stylesheet" href="/library_system/assets/images/css/student/student_topbar.css">
    <link rel="stylesheet" href="/library_system/assets/images/css/student/student_dashboard.css">
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<?php include __DIR__ . '/student_leftsidebar.php'; ?>

<div class="main-wrapper">

    <?php include __DIR__ . '/student_topbar.php'; ?>

    <main class="page-content">

        <!-- WELCOME BANNER -->
        <div class="welcome-banner">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <h1>Good Day, <?= explode(' ', $_SESSION['fullname'])[0] ?>! 👋</h1>
                    <p>What resource or research material are you looking for today?</p>
                    
                    <form action="/library_system/index.php" method="GET" class="search-hero">
                        <input type="hidden" name="action" value="student_search">
                        <input type="text" name="query" placeholder="Search by title, author, or subject...">
                        <button type="submit">Search</button>
                    </form>
                </div>
                <div class="col-lg-5 d-none d-lg-block text-center">
                    <img src="/library_system/assets/images/olivarez_logo.png" alt="OC" style="width: 150px; opacity: 0.8; filter: drop-shadow(0 0 20px rgba(0,0,0,0.2));">
                </div>
            </div>
        </div>

        <!-- CATEGORIES QUICK LINK -->
        <div class="section-title mb-3">Browse by Category</div>
        <div class="mb-5 overflow-auto d-flex pb-2">
            <?php foreach(array_slice($categories, 0, 8) as $cat): ?>
            <a href="/library_system/index.php?action=student_search&category=<?= urlencode($cat['category_name']) ?>" class="category-pill">
                <i class="bi bi-tag-fill"></i>
                <?= htmlspecialchars($cat['category_name']) ?>
            </a>
            <?php endforeach; ?>
            <a href="/library_system/index.php?action=student_search" class="category-pill">
                <i class="bi bi-plus-circle-fill"></i>
                View All
            </a>
        </div>

        <!-- RECENT RESOURCES -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="section-title mb-0">Newly Added Materials</div>
            <a href="/library_system/index.php?action=student_search" class="text-decoration-none fw-600 color-oc-green">
                View All <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-4">
            <?php 
            $recent = array_slice($resources, 0, 8);
            if (empty($recent)): ?>
            <div class="col-12 text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                No resources available yet.
            </div>
            <?php else: ?>
            <?php foreach($recent as $r): ?>
            <div class="col-sm-6 col-md-4 col-xl-3">
                <div class="resource-card-student">
                    <div class="resource-thumb">
                        <?php if (!empty($r['cover_image'])): ?>
                            <img src="/library_system/<?= htmlspecialchars($r['cover_image']) ?>" alt="Cover" class="w-100 h-100 object-fit-cover rounded-3">
                        <?php else: ?>
                            <i class="bi bi-journal-text"></i>
                        <?php endif; ?>
                        <span class="type-badge"><?= htmlspecialchars($r['type'] ?? 'Material') ?></span>
                    </div>
                    <div class="resource-body">
                        <div class="resource-category"><?= htmlspecialchars($r['category'] ?? 'General') ?></div>
                        <div class="resource-title"><?= htmlspecialchars($r['title']) ?></div>
                        <div class="resource-author">by <?= htmlspecialchars($r['author']) ?></div>
                    </div>
                    <div class="resource-footer">
                        <div class="status-indicator">
                            <span class="status-dot <?= $r['status'] === 'available' ? 'dot-available' : 'dot-borrowed' ?>"></span>
                            <?= ucfirst($r['status']) ?>
                        </div>
                        <button type="button" 
                                class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-600"
                                onclick='viewDetails(<?= htmlspecialchars(json_encode($r), ENT_QUOTES, 'UTF-8') ?>)'>
                            View
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Resource Details Modal -->
        <div class="modal fade" id="resourceModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                    <div class="modal-header border-0 pb-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 pt-0">
                        <div class="text-center mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-4 mb-3" style="width: 70px; height: 70px; font-size: 2rem;">
                                <i class="bi bi-book"></i>
                            </div>
                            <h4 class="fw-800 mb-1" id="modalTitle">Resource Title</h4>
                            <p class="text-muted" id="modalAuthor">by Author Name</p>
                        </div>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <div class="p-3 bg-light rounded-3">
                                    <small class="text-muted d-block mb-1">Category</small>
                                    <span class="fw-600" id="modalCategory">General</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded-3">
                                    <small class="text-muted d-block mb-1">Status</small>
                                    <span id="modalStatusBadge">Available</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-700">Description</h6>
                            <p class="text-muted small mb-0" id="modalDescription">No description available.</p>
                        </div>

                        <div class="row g-2" id="modalActions">
                            <div class="col-12" id="borrowAction">
                                <!-- Borrow button will be injected here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('sidebarOverlay').classList.toggle('show');
    }

    const resourceModal = new bootstrap.Modal(document.getElementById('resourceModal'));

    function viewDetails(resource) {
        document.getElementById('modalTitle').textContent = resource.title;
        document.getElementById('modalAuthor').textContent = 'by ' + resource.author;
        document.getElementById('modalCategory').textContent = resource.category || 'General';
        document.getElementById('modalDescription').textContent = resource.description || 'No description provided for this material.';
        
        const statusBadge = document.getElementById('modalStatusBadge');
        const borrowAction = document.getElementById('borrowAction');
        
        // Handle Borrow Button
        if (resource.status === 'available') {
            statusBadge.innerHTML = '<span class="badge bg-success-subtle text-success rounded-pill px-3">Available</span>';
            borrowAction.innerHTML = `
                <a href="/library_system/index.php?action=student_borrow&id=${resource.id}" class="btn btn-primary w-100 rounded-pill py-2 fw-700">
                    <i class="bi bi-bookmark-plus me-2"></i> Borrow
                </a>`;
        } else {
            statusBadge.innerHTML = '<span class="badge bg-warning-subtle text-warning rounded-pill px-3">Borrowed</span>';
            borrowAction.innerHTML = `
                <button class="btn btn-secondary w-100 rounded-pill py-2 fw-700" disabled>
                    <i class="bi bi-lock-fill me-2"></i> Unavailable
                </button>`;
        }
        
        resourceModal.show();
    }
</script>
</body>
</html>
