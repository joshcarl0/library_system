<?php
/**
 * @var array $resources
 * @var array $categories
 */
$search_query = $_GET['query'] ?? '';
$search_cat   = $_GET['category'] ?? '';
$search_type  = $_GET['type'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Resources – LRIS | Olivarez College</title>
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
    <link rel="stylesheet" href="/library_system/assets/images/css/student/search_resources.css">
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<?php include __DIR__ . '/student_leftsidebar.php'; ?>

<div class="main-wrapper">

    <?php include __DIR__ . '/student_topbar.php'; ?>

    <main class="page-content">

        <div class="section-title mb-2">Search Learning Resources</div>
        <p class="text-muted mb-4">Discover books, journals, theses, and other academic materials.</p>

        <!-- SEARCH & FILTER BOX -->
        <div class="search-header-box">
            <form action="/library_system/index.php" method="GET">
                <input type="hidden" name="action" value="student_search">
                <div class="filter-grid">
                    <div class="filter-group">
                        <label>Search Query</label>
                        <input type="text" name="query" class="form-control" placeholder="Search by title, author, or keywords..." value="<?= htmlspecialchars($search_query) ?>">
                    </div>

                    <div class="filter-group">
                        <label>Category</label>
                        <select name="category" class="form-select" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            <?php foreach($categories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat['category_name'] ?? '') ?>" <?= ($search_cat == ($cat['category_name'] ?? '')) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['category_name'] ?? '') ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Resource Type</label>
                        <select name="type" class="form-select" onchange="this.form.submit()">
                            <option value="">All Types</option>
                            <option value="book" <?= ($search_type === 'book') ? 'selected' : '' ?>>Book</option>
                            <option value="journal" <?= ($search_type === 'journal') ? 'selected' : '' ?>>Journal</option>
                            <option value="thesis" <?= ($search_type === 'thesis') ? 'selected' : '' ?>>Thesis</option>
                            <option value="module" <?= ($search_type === 'module') ? 'selected' : '' ?>>Module</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <button type="submit" class="btn-search-apply">
                            <i class="bi bi-search"></i> Apply Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>


        <!-- RESULTS INFO & GRID -->
        <?php if (empty($resources)): ?>
            <div class="results-count mb-3">
                <i class="bi bi-info-circle"></i>
                Found <strong>0</strong> materials for your search.
            </div>
            <div class="row g-4">
                <div class="col-12">
                    <div class="empty-results">
                        <i class="bi bi-search-heart"></i>
                        <h3>No materials found</h3>
                        <p>We couldn't find any resources matching your criteria. Try adjusting your filters or searching for something else.</p>
                        <a href="/library_system/index.php?action=student_search" class="btn btn-outline-primary mt-3 rounded-pill">Clear All Filters</a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="results-count mb-3">
                <i class="bi bi-info-circle"></i>
                Found <strong><?= count($resources) ?></strong> materials for your search.
            </div>
            <div class="row g-4">
                <?php foreach($resources as $r): ?>
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
                                <span class="status-dot <?= $r['status'] === 'available' ? 'dot-available' : ($r['status'] === 'pending' ? 'dot-pending' : 'dot-borrowed') ?>"></span>
                                <?= ucfirst($r['status']) ?> (<?= (int)$r['stock'] ?> left)
                            </div>
                            <button class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-600" 
                                    onclick="viewDetails(<?= htmlspecialchars(json_encode($r), ENT_QUOTES, 'UTF-8') ?>)">
                                View Details
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- PAGINATION -->
        <?php if (!empty($resources)): ?>
        <div class="pagination-wrap">
            <nav>
                <ul class="pagination pagination-sm">
                    <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
                </ul>
            </nav>
        </div>
        <?php endif; ?>

    </main>
</div>

<!-- Resource Details Modal -->
<div class="modal fade" id="resourceDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <div class="text-center mb-4">
                    <div class="book-container mb-4" id="bookContainer">
                        <div class="book initial-angle" id="modalBook3D">
                            <div class="book-face book-front">
                                <div id="modalDefaultCover" class="default-cover">
                                    <div class="cover-border">
                                        <h6 id="modalCoverTitle" class="cover-title"></h6>
                                        <span class="cover-author" id="modalCoverAuthor"></span>
                                    </div>
                                </div>
                                <img src="" id="modalCoverImg3D" class="d-none">
                            </div>
                            <div class="book-face book-back"></div>
                            <div class="book-face book-spine"></div>
                            <div class="book-face book-pages-right"></div>
                            <div class="book-face book-pages-top"></div>
                            <div class="book-face book-pages-bottom"></div>
                        </div>
                    </div>
                    <h5 class="fw-800 mb-1" id="modalTitle"></h5>
                    <p class="text-muted mb-2" id="modalAuthor"></p>
                    <div id="modalStatusBadge"></div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-700">Description</h6>
                    <p class="text-muted small mb-0" id="modalDescription"></p>
                </div>

                <div class="row g-2" id="modalActions">
                    <div class="col-12" id="borrowAction"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('sidebarOverlay').classList.toggle('show');
    }

    const resourceModal = new bootstrap.Modal(document.getElementById('resourceDetailsModal'));

    function viewDetails(resource) {
        document.getElementById('modalTitle').textContent = resource.title;
        document.getElementById('modalAuthor').textContent = 'by ' + resource.author;
        document.getElementById('modalDescription').textContent = resource.description || 'No description provided for this material.';
        
        // Reset 3D book rotation
        const book3D = document.getElementById('modalBook3D');
        book3D.style.transform = ''; // remove inline styles to fallback to initial-angle
        book3D.classList.add('initial-angle');
        currentRotX = -15;
        currentRotY = -30;

        // Handle Cover Image
        const coverImg = document.getElementById('modalCoverImg3D');
        const defaultCover = document.getElementById('modalDefaultCover');
        const coverTitle = document.getElementById('modalCoverTitle');
        const coverAuthor = document.getElementById('modalCoverAuthor');

        if (resource.cover_image) {
            coverImg.src = '/library_system/' + resource.cover_image;
            coverImg.classList.remove('d-none');
            defaultCover.classList.add('d-none');
        } else {
            coverImg.classList.add('d-none');
            defaultCover.classList.remove('d-none');
            coverTitle.textContent = resource.title;
            coverAuthor.textContent = 'by ' + resource.author;
        }

        const statusBadge = document.getElementById('modalStatusBadge');
        const borrowAction = document.getElementById('borrowAction');
        
        // Handle Borrow Button
        if (resource.status === 'available' && parseInt(resource.stock) > 0) {
            statusBadge.innerHTML = `<span class="badge bg-success-subtle text-success rounded-pill px-3">Available (${resource.stock} left)</span>`;
            borrowAction.innerHTML = `
                <a href="/library_system/index.php?action=student_borrow&id=${resource.id}" class="btn btn-primary w-100 rounded-pill py-2 fw-700">
                    <i class="bi bi-bookmark-plus me-2"></i> Borrow
                </a>`;
        } else if (resource.status === 'pending') {
            statusBadge.innerHTML = '<span class="badge bg-info-subtle text-info rounded-pill px-3">Pending Approval</span>';
            borrowAction.innerHTML = `
                <button class="btn btn-info w-100 rounded-pill py-2 fw-700 text-white" disabled>
                    <i class="bi bi-clock me-2"></i> Requested
                </button>`;
        } else {
            statusBadge.innerHTML = '<span class="badge bg-warning-subtle text-warning rounded-pill px-3">Borrowed</span>';
            borrowAction.innerHTML = `
                <button class="btn btn-secondary w-100 rounded-pill py-2 fw-700" disabled>
                    <i class="bi bi-lock-fill me-2"></i> Unavailable
                </button>`;
        }
        
        resourceModal.show();
    }

    // 3D Book Drag to Rotate Logic
    const bookContainer = document.getElementById('bookContainer');
    const book3DElem = document.getElementById('modalBook3D');
    
    let isDragging = false;
    let startX, startY;
    let currentRotX = -15;
    let currentRotY = -30;

    function handleDragStart(e) {
        isDragging = true;
        startX = e.type.includes('mouse') ? e.pageX : e.touches[0].pageX;
        startY = e.type.includes('mouse') ? e.pageY : e.touches[0].pageY;
        book3DElem.classList.remove('initial-angle');
        book3DElem.style.transition = 'none'; // disable transition while dragging
    }

    function handleDragMove(e) {
        if (!isDragging) return;
        e.preventDefault(); // prevent scrolling while dragging book

        const x = e.type.includes('mouse') ? e.pageX : e.touches[0].pageX;
        const y = e.type.includes('mouse') ? e.pageY : e.touches[0].pageY;

        const deltaX = x - startX;
        const deltaY = y - startY;

        currentRotY += deltaX * 0.5;
        currentRotX -= deltaY * 0.5;

        // Limit X rotation so it doesn't flip completely upside down in a weird way
        currentRotX = Math.max(-90, Math.min(90, currentRotX));

        book3DElem.style.transform = `rotateX(${currentRotX}deg) rotateY(${currentRotY}deg)`;

        startX = x;
        startY = y;
    }

    function handleDragEnd() {
        if (!isDragging) return;
        isDragging = false;
        book3DElem.style.transition = 'transform 0.1s ease-out'; // re-enable transition
    }

    bookContainer.addEventListener('mousedown', handleDragStart);
    document.addEventListener('mousemove', handleDragMove);
    document.addEventListener('mouseup', handleDragEnd);

    bookContainer.addEventListener('touchstart', handleDragStart, {passive: false});
    document.addEventListener('touchmove', handleDragMove, {passive: false});
    document.addEventListener('touchend', handleDragEnd);

    // No live search needed as we use server-side "Apply Filters" button for better accuracy
</script>
</body>
</html>
