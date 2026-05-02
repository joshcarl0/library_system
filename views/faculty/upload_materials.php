<?php
/**
 * Faculty — Upload Materials View
 * @var string $message
 * @var string $msgType
 * @var array  $categories
 * @var array  $subjects
 */
// Safety fallbacks — prevent undefined variable errors
$message    = $message    ?? '';
$msgType    = $msgType    ?? '';
$categories = $categories ?? [];
$subjects   = $subjects   ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Materials – LRIS | Olivarez College</title>
    <meta name="description" content="Faculty upload portal for the Olivarez College Learning Resource Information System.">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Faculty Styles -->
    <link rel="stylesheet" href="/library_system/assets/images/css/faculty/faculty_sidebar.css">
    <link rel="stylesheet" href="/library_system/assets/images/css/faculty/faculty_dashboard.css">
    <link rel="stylesheet" href="/library_system/assets/images/css/faculty/upload_materials.css">
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<?php include __DIR__ . '/faculty_leftsidebar.php'; ?>

<div class="main-wrapper">

    <?php include __DIR__ . '/faculty_topbar.php'; ?>

    <main class="page-content">

        <!-- Alert Message -->
        <?php if (!empty($message)): ?>
        <div class="alert-faculty <?= htmlspecialchars($msgType) ?>" id="uploadAlert">
            <i class="bi bi-<?= $msgType === 'success' ? 'check-circle-fill' : 'exclamation-circle-fill' ?>"></i>
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php endif; ?>

        <!-- Page Header -->
        <div class="section-title mb-4">Upload Learning Material</div>

        <div class="upload-wrapper">
            <!-- Main Upload Card -->
            <div class="upload-card-faculty mb-4">

                <!-- Card Header -->
                <div class="upload-card-header">
                    <div class="uch-icon">
                        <i class="bi bi-cloud-arrow-up-fill"></i>
                    </div>
                    <div>
                        <h2>Submit a New Resource</h2>
                        <p>Fill in the details and attach your file to share with students.</p>
                    </div>
                </div>

                <!-- Card Body / Form -->
                <div class="upload-card-body">
                    <form action="/library_system/index.php?action=faculty_upload" method="POST" enctype="multipart/form-data">

                        <!-- ── File Drop Zone ── -->
                        <div class="mb-4">
                            <label class="fu-label">Attach File</label>
                            <div class="file-drop-area" id="dropArea">
                                <i class="bi bi-cloud-arrow-up drop-icon"></i>
                                <div class="drop-title">Click to browse or drag &amp; drop</div>
                                <div class="drop-sub">Allowed: PDF, DOCX, PPTX, ZIP, TXT &nbsp;·&nbsp; Max 50MB</div>
                                <input type="file" name="material_file" id="fileInput" class="file-input" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.txt" required>
                            </div>
                            <div class="selected-file-badge" id="selectedBadge">
                                <i class="bi bi-file-earmark-check-fill"></i>
                                <span id="selectedFileName">No file chosen</span>
                            </div>
                        </div>

                        <div class="form-divider"></div>

                        <!-- ── Resource Details ── -->
                        <div class="row g-4">

                            <!-- Title -->
                            <div class="col-12">
                                <label class="fu-label" for="res_title">Resource Title <span class="text-danger">*</span></label>
                                <input type="text" id="res_title" name="title" class="fu-input"
                                       placeholder="e.g. Introduction to Data Structures" required>
                            </div>

                            <!-- Author -->
                            <div class="col-md-6">
                                <label class="fu-label" for="res_author">Author / Publisher <span class="text-danger">*</span></label>
                                <input type="text" id="res_author" name="author" class="fu-input"
                                       placeholder="Full name or organization" required>
                            </div>

                            <!-- Subject -->
                            <div class="col-md-6">
                                <label class="fu-label" for="res_subject">Subject</label>
                                <select id="res_subject" name="subject" class="fu-input">
                                    <option value="">— Select Subject —</option>
                                    <?php foreach ($subjects as $sub): ?>
                                    <option value="<?= htmlspecialchars($sub['name']) ?>">
                                        <?= htmlspecialchars($sub['name']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Category -->
                            <div class="col-md-6">
                                <label class="fu-label" for="res_category">Category</label>
                                <select id="res_category" name="category" class="fu-input">
                                    <option value="">— Select Category —</option>
                                    <?php foreach ($categories as $cat): ?>
                                    <option value="<?= htmlspecialchars($cat['category_name']) ?>">
                                        <?= htmlspecialchars($cat['category_name']) ?>
                                        <?php if (!empty($cat['resource_type'])): ?>(<?= htmlspecialchars($cat['resource_type']) ?>)<?php endif; ?>
                                    </option>
                                    <?php endforeach; ?>
                                    <?php if (empty($categories)): ?>
                                    <option disabled>No categories found — ask admin to add some</option>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <!-- Material Type -->
                            <div class="col-md-6">
                                <label class="fu-label" for="res_type">Material Type</label>
                                <select id="res_type" name="type" class="fu-input">
                                    <option value="digital">Digital File (PDF / E-book)</option>
                                    <option value="module">Learning Module</option>
                                    <option value="thesis">Thesis / Research Paper</option>
                                    <option value="journal">Journal Article</option>
                                    <option value="book">Physical Book Record</option>
                                </select>
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <label class="fu-label" for="res_desc">Description / Abstract</label>
                                <textarea id="res_desc" name="description" class="fu-input" rows="4"
                                          placeholder="Brief summary or abstract of the material..."></textarea>
                            </div>

                            <!-- Submit -->
                            <div class="col-12 pt-2">
                                <button type="submit" class="btn-upload-submit" id="submitBtn">
                                    <i class="bi bi-cloud-arrow-up-fill"></i>
                                    Confirm &amp; Upload Material
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            <!-- Tips Card -->
            <div class="upload-tips">
                <h6><i class="bi bi-lightbulb-fill"></i> Upload Guidelines</h6>
                <ul>
                    <li>Only upload materials that you are authorized to share (your own work or properly licensed content).</li>
                    <li>Accepted formats: <strong>PDF, DOCX, PPTX, ZIP, TXT</strong> — maximum file size is <strong>50MB</strong>.</li>
                    <li>Provide an accurate <strong>Title</strong> and <strong>Author</strong> so students can easily find the material.</li>
                    <li>Uploaded files will be reviewed and made available to students immediately.</li>
                </ul>
            </div>

        </div><!-- /.upload-wrapper -->

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('sidebarOverlay').classList.toggle('show');
    }

    // ── File drag-and-drop & preview ──────────────────
    const fileInput  = document.getElementById('fileInput');
    const dropArea   = document.getElementById('dropArea');
    const badge      = document.getElementById('selectedBadge');
    const badgeName  = document.getElementById('selectedFileName');
    const submitBtn  = document.getElementById('submitBtn');

    function showFile(file) {
        if (!file) return;
        badgeName.textContent = file.name;
        badge.style.display = 'flex';
        dropArea.classList.add('active');
    }

    fileInput.addEventListener('change', function () {
        if (this.files && this.files[0]) showFile(this.files[0]);
    });

    // Drag visual cues
    ['dragenter', 'dragover'].forEach(e => {
        dropArea.addEventListener(e, ev => {
            ev.preventDefault();
            dropArea.classList.add('active');
        });
    });

    ['dragleave'].forEach(e => {
        dropArea.addEventListener(e, ev => {
            ev.preventDefault();
            dropArea.classList.remove('active');
        });
    });

    dropArea.addEventListener('drop', ev => {
        ev.preventDefault();
        const file = ev.dataTransfer.files[0];
        if (file) {
            // Transfer dropped file to the input
            const dt = new DataTransfer();
            dt.items.add(file);
            fileInput.files = dt.files;
            showFile(file);
        }
    });

    // ── Button loading state on submit ──────────────────
    document.querySelector('form').addEventListener('submit', function () {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Uploading…';
    });

    // ── Auto-dismiss alert ──────────────────────────────
    const alert = document.getElementById('uploadAlert');
    if (alert) setTimeout(() => { alert.style.opacity = '0'; alert.style.transition = 'opacity 0.5s'; setTimeout(() => alert.remove(), 500); }, 4000);
</script>
</body>
</html>
