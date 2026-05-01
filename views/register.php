<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account – Olivarez College Library System</title>
    <meta name="description" content="Create your account to access the Olivarez College Library Resource Information System.">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0d3612 0%, #1B5E20 50%, #0a2a0d 100%);
            overflow-x: hidden;
            padding: 30px 16px;
            position: relative;
        }

        /* ── Animated gradient blobs ── */
        .blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(90px);
            opacity: 0.45;
            animation: float 8s ease-in-out infinite;
            z-index: 0;
            pointer-events: none;
        }
        .blob-1 {
            width: 550px; height: 550px;
            background: radial-gradient(circle, #D4AF37, #a07c10);
            top: -150px; left: -120px;
            animation-delay: 0s;
        }
        .blob-2 {
            width: 450px; height: 450px;
            background: radial-gradient(circle, #2e7d32, #1B5E20);
            bottom: -100px; right: -100px;
            animation-delay: -4s;
        }
        .blob-3 {
            width: 300px; height: 300px;
            background: radial-gradient(circle, #D4AF37, #f0d060);
            top: 40%; left: 60%;
            animation-delay: -2s;
            opacity: 0.20;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px) scale(1); }
            50%       { transform: translateY(-30px) scale(1.05); }
        }

        /* ── Glass card ── */
        .auth-card {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 480px;
            background: rgba(255, 255, 255, 0.10);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.20);
            border-radius: 24px;
            padding: 40px 40px 36px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.4);
            animation: slideUp 0.5s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Logo ── */
        .logo-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 28px;
        }
        .logo-wrap img {
            width: 90px;
            height: 90px;
            object-fit: contain;
            border-radius: 50%;
            background: #fff;
            padding: 6px;
            box-shadow: 0 8px 32px rgba(212, 175, 55, 0.4);
            margin-bottom: 14px;
        }
        .logo-wrap h1 {
            font-size: 1.4rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: 0.5px;
            text-align: center;
            line-height: 1.2;
        }
        .logo-wrap p {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.65);
            margin-top: 6px;
            text-align: center;
        }

        /* ── Section divider label ── */
        .section-label {
            font-size: 0.70rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.35);
            margin-bottom: 12px;
            margin-top: 20px;
        }

        /* ── Form labels ── */
        .form-label {
            color: rgba(255,255,255,0.85);
            font-size: 0.82rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        /* ── Input groups ── */
        .input-group {
            border-radius: 12px;
            overflow: hidden;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .input-group:focus-within {
            border-color: rgba(212, 175, 55, 0.8);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.20);
        }
        .input-group.is-invalid-group {
            border-color: rgba(239, 68, 68, 0.7);
        }
        .input-group-text {
            background: transparent;
            border: none;
            color: rgba(255,255,255,0.45);
            padding-left: 14px;
        }
        .form-control {
            background: transparent;
            border: none;
            color: #fff;
            font-size: 0.92rem;
            padding: 13px 14px;
        }
        .form-control::placeholder { color: rgba(255,255,255,0.30); }
        .form-control:focus {
            background: transparent;
            color: #fff;
            box-shadow: none;
        }
        .form-select {
            background-color: transparent;
            border: none;
            color: #fff;
            font-size: 0.92rem;
            padding: 13px 14px;
        }
        .form-select option { background: #0d3612; color: #fff; }
        .form-select:focus { box-shadow: none; background-color: transparent; }

        .btn-icon {
            background: transparent;
            border: none;
            color: rgba(255,255,255,0.45);
            padding: 0 14px;
            cursor: pointer;
            transition: color 0.2s;
        }
        .btn-icon:hover { color: rgba(255,255,255,0.9); }

        /* ── Password strength bar ── */
        .strength-bar-wrap {
            margin-top: 8px;
            height: 4px;
            border-radius: 4px;
            background: rgba(255,255,255,0.10);
            overflow: hidden;
        }
        .strength-bar {
            height: 100%;
            border-radius: 4px;
            width: 0%;
            transition: width 0.3s, background-color 0.3s;
        }
        .strength-text {
            font-size: 0.72rem;
            margin-top: 4px;
            color: rgba(255,255,255,0.45);
        }

        /* ── Invalid feedback ── */
        .invalid-msg {
            font-size: 0.75rem;
            color: #fca5a5;
            margin-top: 5px;
            display: none;
        }
        .invalid-msg.show { display: block; }

        /* ── Register button ── */
        .btn-register {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #1B5E20;
            background: linear-gradient(135deg, #D4AF37 0%, #f0d060 100%);
            box-shadow: 0 6px 24px rgba(212, 175, 55, 0.45);
            transition: transform 0.15s, box-shadow 0.15s, opacity 0.15s;
            cursor: pointer;
            margin-top: 8px;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 32px rgba(212, 175, 55, 0.60);
            opacity: 0.93;
        }
        .btn-register:active { transform: translateY(0); }

        /* ── Divider ── */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255,255,255,0.30);
            font-size: 0.80rem;
            margin: 20px 0;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.15);
        }

        /* ── Bottom link ── */
        .bottom-text {
            text-align: center;
            color: rgba(255,255,255,0.55);
            font-size: 0.85rem;
        }
        .bottom-text a {
            color: #D4AF37;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }
        .bottom-text a:hover { color: #f0d060; }

        /* ── Alert ── */
        .alert-glass {
            border-radius: 10px;
            font-size: 0.85rem;
            padding: 10px 14px;
            margin-bottom: 18px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }
        .alert-glass.error {
            background: rgba(239, 68, 68, 0.18);
            border: 1px solid rgba(239, 68, 68, 0.40);
            color: #fca5a5;
        }
        .alert-glass.success {
            background: rgba(34, 197, 94, 0.18);
            border: 1px solid rgba(34, 197, 94, 0.40);
            color: #86efac;
        }

        /* ── Terms text ── */
        .terms-text {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.40);
            text-align: center;
            margin-top: 14px;
        }
        .terms-text a { color: #D4AF37; text-decoration: none; }
        .terms-text a:hover { text-decoration: underline; }

        @media (max-width: 480px) {
            .auth-card { padding: 28px 22px; }
        }
    </style>
</head>
<body>

    <!-- Animated blobs -->
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    <!-- Auth Card -->
    <div class="auth-card">

        <!-- Logo -->
        <div class="logo-wrap">
            <img src="/library_system/assets/images/olivarez_logo.png" alt="Olivarez College Logo">
            <h1>Olivarez College</h1>
            <p>Create your Library Account</p>
        </div>

        <!-- Alerts (PHP will populate) -->
        <?php if (!empty($error)): ?>
        <div class="alert-glass error">
            <i class="bi bi-exclamation-circle-fill mt-1"></i>
            <span><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></span>
        </div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
        <div class="alert-glass success">
            <i class="bi bi-check-circle-fill mt-1"></i>
            <span><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></span>
        </div>
        <?php endif; ?>

        <!-- Register Form -->
        <form id="registerForm" method="POST" action="/library_system/index.php?action=register" novalidate>

            <!-- Personal Info section -->
            <div class="section-label">Personal Information</div>

            <!-- Full Name -->
            <div class="mb-3">
                <label for="fullname" class="form-label">Full Name</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input
                        type="text"
                        id="fullname"
                        name="fullname"
                        class="form-control"
                        placeholder="Juan Dela Cruz"
                        value="<?= htmlspecialchars($_POST['fullname'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        required
                        autocomplete="name"
                    >
                </div>
                <div class="invalid-msg" id="fullname-err">Please enter your full name.</div>
            </div>

            <!-- ID Number -->
            <div class="mb-3">
                <label for="student_id" class="form-label">ID Number</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                    <input
                        type="text"
                        id="student_id"
                        name="student_id"
                        class="form-control"
                        placeholder="e.g. 2024-00001"
                        value="<?= htmlspecialchars($_POST['student_id'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        required
                        autocomplete="off"
                    >
                </div>
                <div class="invalid-msg" id="student_id-err">Please enter your ID Number.</div>
            </div>

            <!-- Role Selection -->
            <div class="mb-3">
                <label for="role" class="form-label">I am a</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                    <select class="form-select" id="role" name="role" required>
                        <option value="student" <?= (isset($_POST['role']) && $_POST['role'] === 'student') ? 'selected' : '' ?>>Student</option>
                        <option value="faculty" <?= (isset($_POST['role']) && $_POST['role'] === 'faculty') ? 'selected' : '' ?>>Faculty</option>
                    </select>
                </div>
            </div>

            <!-- Account section -->
            <div class="section-label">Account Details</div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        placeholder="you@email.com"
                        value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        required
                        autocomplete="email"
                    >
                </div>
                <div class="invalid-msg" id="email-err">Please enter a valid email address.</div>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="reg_password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input
                        type="password"
                        id="reg_password"
                        name="password"
                        class="form-control"
                        placeholder="Min. 8 characters"
                        required
                        autocomplete="new-password"
                    >
                    <button type="button" class="btn-icon" id="toggleRegPass" aria-label="Show/hide password">
                        <i class="bi bi-eye-fill" id="regEyeIcon"></i>
                    </button>
                </div>
                <!-- Strength bar -->
                <div class="strength-bar-wrap">
                    <div class="strength-bar" id="strengthBar"></div>
                </div>
                <div class="strength-text" id="strengthText">Enter a password</div>
                <div class="invalid-msg" id="password-err">Password must be at least 8 characters.</div>
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-shield-lock-fill"></i></span>
                    <input
                        type="password"
                        id="confirm_password"
                        name="confirm_password"
                        class="form-control"
                        placeholder="Re-enter your password"
                        required
                        autocomplete="new-password"
                    >
                    <button type="button" class="btn-icon" id="toggleConfirmPass" aria-label="Show/hide confirm password">
                        <i class="bi bi-eye-fill" id="confirmEyeIcon"></i>
                    </button>
                </div>
                <div class="invalid-msg" id="confirm-err">Passwords do not match.</div>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn-register" id="registerBtn">
                <span id="registerText"><i class="bi bi-person-plus-fill me-2"></i>Create Account</span>
                <span id="registerSpinner" class="d-none">
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    Creating account…
                </span>
            </button>

            <div class="divider">or</div>

            <div class="bottom-text">
                Already have an account? <a href="/library_system/index.php?action=login">Sign in</a>
            </div>

            <div class="terms-text mt-3">
                By registering, you agree to the
                <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.
            </div>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ── Toggle password visibility ──
        function makeToggle(btnId, inputId, iconId) {
            document.getElementById(btnId).addEventListener('click', function () {
                const inp  = document.getElementById(inputId);
                const icon = document.getElementById(iconId);
                if (inp.type === 'password') {
                    inp.type = 'text';
                    icon.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
                } else {
                    inp.type = 'password';
                    icon.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
                }
            });
        }
        makeToggle('toggleRegPass',     'reg_password',      'regEyeIcon');
        makeToggle('toggleConfirmPass', 'confirm_password',  'confirmEyeIcon');

        // ── Password strength meter ──
        document.getElementById('reg_password').addEventListener('input', function () {
            const val  = this.value;
            const bar  = document.getElementById('strengthBar');
            const text = document.getElementById('strengthText');
            let score  = 0;

            if (val.length >= 8)                          score++;
            if (/[A-Z]/.test(val))                        score++;
            if (/[0-9]/.test(val))                        score++;
            if (/[^A-Za-z0-9]/.test(val))                 score++;

            const levels = [
                { w: '0%',   color: 'transparent',  label: 'Enter a password' },
                { w: '25%',  color: '#ef4444',       label: '⚠ Weak' },
                { w: '50%',  color: '#f97316',       label: '▲ Fair' },
                { w: '75%',  color: '#eab308',       label: '● Good' },
                { w: '100%', color: '#22c55e',       label: '✔ Strong' },
            ];
            const lvl = val.length === 0 ? levels[0] : levels[score];
            bar.style.width           = lvl.w;
            bar.style.backgroundColor = lvl.color;
            text.textContent          = lvl.label;
        });

        // ── Client-side form validation ──
        document.getElementById('registerForm').addEventListener('submit', function (e) {
            let valid = true;

            const fields = [
                { id: 'fullname',         errId: 'fullname-err',   check: v => v.trim().length >= 2 },
                { id: 'student_id',       errId: 'student_id-err', check: v => v.trim().length >= 3 },
                { id: 'email',            errId: 'email-err',      check: v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v) },
                { id: 'reg_password',     errId: 'password-err',   check: v => v.length >= 8 },
            ];

            fields.forEach(f => {
                const el  = document.getElementById(f.id);
                const err = document.getElementById(f.errId);
                if (!f.check(el.value)) {
                    err.classList.add('show');
                    el.closest('.input-group').classList.add('is-invalid-group');
                    valid = false;
                } else {
                    err.classList.remove('show');
                    el.closest('.input-group').classList.remove('is-invalid-group');
                }
            });

            // Confirm password
            const pw  = document.getElementById('reg_password').value;
            const cpw = document.getElementById('confirm_password').value;
            const ce  = document.getElementById('confirm-err');
            if (pw !== cpw) {
                ce.classList.add('show');
                document.getElementById('confirm_password').closest('.input-group').classList.add('is-invalid-group');
                valid = false;
            } else {
                ce.classList.remove('show');
                document.getElementById('confirm_password').closest('.input-group').classList.remove('is-invalid-group');
            }

            if (!valid) { e.preventDefault(); return; }

            // Loading state
            document.getElementById('registerText').classList.add('d-none');
            document.getElementById('registerSpinner').classList.remove('d-none');
            document.getElementById('registerBtn').disabled = true;
        });

        // ── Clear error highlight on input ──
        document.querySelectorAll('.form-control').forEach(el => {
            el.addEventListener('input', function () {
                this.closest('.input-group').classList.remove('is-invalid-group');
            });
        });
    </script>
</body>
</html>
