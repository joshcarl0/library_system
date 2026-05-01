<?php
/**
 * @var string $message
 * @var string $msgType
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Materials – LRIS | Olivarez College</title>
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
    <link rel="stylesheet" href="/library_system/assets/images/css/upload_materials.css">
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
        <div class="section-title mb-4">Upload New Learning Material</div>

        <div class="upload-card">
            <div class="content-card p-4">
                <form action="/library_system/index.php?action=admin_upload_materials" method="POST" enctype="multipart/form-data">
                    <div class="row g-4">
                        
                        <!-- File Upload Area -->
                        <div class="col-12">
                            <div class="modal-label">Select File</div>
                            <div class="file-drop-area" id="dropArea">
                                <i class="bi bi-cloud-arrow-up"></i>
                                <p>Click to browse or drag and drop your file here</p>
                                <small class="text-muted mt-2">Allowed: PDF, DOCX, PPTX, ZIP, TXT (Max: 50MB)</small>
                                <input type="file" name="material_file" id="fileInput" class="file-input" required>
                                <div id="fileInfo" class="selected-file-info">
                                    <i class="bi bi-file-earmark-check me-1"></i>
                                    <span id="fileName">No file chosen</span>
                                </div>
                            </div>
                        </div>

                        <!-- Title -->
                        <div class="col-md-12">
                            <label class="modal-label">Resource Title</label>
                            <input type="text" name="title" class="modal-input" placeholder="Enter title of the material" required>
                        </div>

                        <!-- Author -->
                        <div class="col-md-6">
                            <label class="modal-label">Author / Publisher</label>
                            <input type="text" name="author" class="modal-input" placeholder="Full name or organization" required>
                        </div>

                        <!-- Subject -->
                        <div class="col-md-6">
                            <label class="modal-label">Subject</label>
                            <input type="text" name="subject" class="modal-input" placeholder="e.g. Computer Science, History">
                        </div>

                        <!-- Category -->
                        <div class="col-md-6">
                            <label class="modal-label">Category</label>
                            <input type="text" name="category" class="modal-input" placeholder="e.g. Academic, General, Research">
                        </div>

                        <!-- Resource Type -->
                        <div class="col-md-6">
                            <label class="modal-label">Material Type</label>
                            <select name="type" class="modal-input" style="appearance: auto;">
                                <option value="digital">Digital File (PDF/E-book)</option>
                                <option value="module">Learning Module</option>
                                <option value="thesis">Thesis / Research Paper</option>
                                <option value="journal">Journal Article</option>
                                <option value="book">Physical Book Record</option>
                            </select>
                        </div>

                        <!-- Description -->
                        <div class="col-12">
                            <label class="modal-label">Description / Abstract</label>
                            <textarea name="description" class="modal-input" rows="4" placeholder="Brief summary of the material..."></textarea>
                        </div>

                        <div class="col-12 pt-2">
                            <button type="submit" class="btn-oc-gold w-100 py-3">
                                <i class="bi bi-cloud-arrow-up-fill me-2"></i> Confirm and Upload Material
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Sidebar toggle
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('sidebarOverlay').classList.toggle('show');
    }

    // File Input Interaction
    const fileInput = document.getElementById('fileInput');
    const dropArea = document.getElementById('dropArea');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');

    fileInput.addEventListener('change', function() {
        if (this.files && this.files.length > 0) {
            fileName.textContent = this.files[0].name;
            fileInfo.style.display = 'block';
            dropArea.classList.add('active');
        }
    });

    // Drag and drop visual cues
    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropArea.classList.add('active');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropArea.classList.remove('active');
        }, false);
    });

    // Auto-dismiss alert
    const alert = document.querySelector('.alert-oc');
    if (alert) setTimeout(() => alert.style.display = 'none', 5000);
</script>
</body>
</html>
