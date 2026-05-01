<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In – Olivarez College Library System</title>
    <meta name="description" content="Sign in to access the Olivarez College Library Resource Information System.">

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
            overflow: hidden;
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
            bottom: 80px; left: 35%;
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
            max-width: 440px;
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
        .btn-icon {
            background: transparent;
            border: none;
            color: rgba(255,255,255,0.45);
            padding: 0 14px;
            cursor: pointer;
            transition: color 0.2s;
        }
        .btn-icon:hover { color: rgba(255,255,255,0.9); }

        /* ── Forgot password ── */
        .forgot-link {
            font-size: 0.80rem;
            color: #D4AF37;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        .forgot-link:hover { color: #f0d060; text-decoration: underline; }

        /* ── Sign In button ── */
        .btn-signin {
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
        }
        .btn-signin:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 32px rgba(212, 175, 55, 0.60);
            opacity: 0.93;
        }
        .btn-signin:active { transform: translateY(0); }

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
            background: rgba(239, 68, 68, 0.18);
            border: 1px solid rgba(239, 68, 68, 0.40);
            border-radius: 10px;
            color: #fca5a5;
            font-size: 0.85rem;
            padding: 10px 14px;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
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
            <p>Library Resource Information System</p>
        </div>

        <!-- Error message (PHP will populate) -->
        <?php if (!empty($error)): ?>
        <div class="alert-glass">
            <i class="bi bi-exclamation-circle-fill"></i>
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php endif; ?>

        <!-- Success message from password reset -->
        <?php if (isset($_GET['reset']) && $_GET['reset'] === 'success'): ?>
        <div class="alert-glass" style="background: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.2); color: #6ee7b7;">
            <i class="bi bi-check-circle-fill"></i>
            Password has been reset successfully. You can now log in.
        </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form id="loginForm" method="POST" action="/library_system/index.php?action=login" novalidate>

            <!-- Student ID / Email -->
            <div class="mb-3">
                <label for="identifier" class="form-label">ID Number or Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input
                        type="text"
                        id="identifier"
                        name="identifier"
                        class="form-control"
                        placeholder="Enter ID number or email"
                        value="<?= htmlspecialchars($_POST['identifier'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        required
                        autocomplete="username"
                    >
                </div>
            </div>

            <!-- Password -->
            <div class="mb-2">
                <label for="password" class="form-label">Password</label>

                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="Enter your password"
                        required
                        autocomplete="current-password"
                    >
                    <button type="button" class="btn-icon" id="togglePassword" aria-label="Show/hide password">
                        <i class="bi bi-eye-fill" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <!-- Forgot password -->
            <div class="mb-4 text-end">
                <a href="/library_system/index.php?action=forgot_password" class="forgot-link">Forgot password?</a>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn-signin" id="signinBtn">
                <span id="signinText">Sign In</span>
                <span id="signinSpinner" class="d-none">
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    Signing in…
                </span>
            </button>

            <div class="divider">or</div>

            <div class="bottom-text">
                Don't have an account? <a href="/library_system/index.php?action=register">Sign up</a>
            </div>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function () {
            const pwd  = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
            } else {
                pwd.type = 'password';
                icon.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
            }
        });

        // Loading state on submit
        document.getElementById('loginForm').addEventListener('submit', function () {
            document.getElementById('signinText').classList.add('d-none');
            document.getElementById('signinSpinner').classList.remove('d-none');
            document.getElementById('signinBtn').disabled = true;
        });
    </script>
</body>
</html>
