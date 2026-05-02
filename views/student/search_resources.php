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
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($search_cat == $cat['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['category_name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Resource Type</label>
                        <select name="type" class="form-select">
                            <option value="">All Types</option>
                            <option value="Book" <?= ($search_type === 'Book') ? 'selected' : '' ?>>Book</option>
                            <option value="E-Book" <?= ($search_type === 'E-Book') ? 'selected' : '' ?>>E-Book</option>
                            <option value="Journal" <?= ($search_type === 'Journal') ? 'selected' : '' ?>>Journal</option>
                            <option value="Thesis" <?= ($search_type === 'Thesis') ? 'selected' : '' ?>>Thesis</option>
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

        <!-- RESULTS INFO -->
        <div class="results-count">
            <i class="bi bi-info-circle"></i>
            Found <strong><?= count($resources) ?></strong> materials for your search.
        </div>

        <!-- RESULTS GRID -->
        <div class="row g-4">
            <?php if (empty($resources)): ?>
            <div class="col-12">
                <div class="empty-results">
                    <i class="bi bi-search-heart"></i>
                    <h3>No materials found</h3>
                    <p>We couldn't find any resources matching your criteria. Try adjusting your filters or searching for something else.</p>
                    <a href="/library_system/index.php?action=student_search" class="btn btn-outline-primary mt-3 rounded-pill">Clear All Filters</a>
                </div>
            </div>
            <?php else: ?>
            <?php foreach($resources as $r): ?>
            <div class="col-sm-6 col-md-4 col-xl-3">
                <div class="resource-card-student">
                    <div class="resource-thumb">
                        <i class="bi bi-journal-text"></i>
                        <span class="type-badge"><?= htmlspecialchars($r['resource_type']) ?></span>
                    </div>
                    <div class="resource-body">
                        <div class="resource-category"><?= htmlspecialchars($r['category_name'] ?? 'General') ?></div>
                        <div class="resource-title"><?= htmlspecialchars($r['title']) ?></div>
                        <div class="resource-author">by <?= htmlspecialchars($r['author']) ?></div>
                    </div>
                    <div class="resource-footer">
                        <div class="status-indicator">
                            <span class="status-dot <?= $r['status'] === 'available' ? 'dot-available' : 'dot-borrowed' ?>"></span>
                            <?= ucfirst($r['status']) ?>
                        </div>
                        <button class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-600">View Details</button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- PAGINATION (Placeholder) -->
        <?php if (!empty($resources)): ?>
        <div class="pagination-wrap">
            <nav>
                <ul class="pagination pagination-sm">
                    <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </nav>
        </div>
        <?php endif; ?>

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('sidebarOverlay').classList.toggle('show');
    }
</script>
</body>
</html>
