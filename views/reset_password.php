<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password – Olivarez College</title>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        /* Shared Styles matching login.php */
        :root {
            --glass-bg: rgba(255, 255, 255, 0.08);
            --glass-border: rgba(255, 255, 255, 0.1);
            --glass-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            --primary-accent: #4f46e5;
            --primary-hover: #4338ca;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --error-color: #ef4444;
            --success-color: #10b981;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #0f172a;
            color: var(--text-main);
            overflow: hidden;
            position: relative;
        }

        /* Animated Background Blobs */
        .blob {
            position: absolute;
            filter: blur(80px);
            z-index: -1;
            opacity: 0.6;
            animation: float 10s infinite ease-in-out alternate;
        }
        .blob-1 {
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, #4f46e5, #ec4899);
            top: -100px;
            left: -100px;
            border-radius: 50%;
        }
        .blob-2 {
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, #0ea5e9, #3b82f6);
            bottom: -50px;
            right: -50px;
            border-radius: 50%;
            animation-delay: -5s;
        }

        @keyframes float {
            0% { transform: translateY(0) scale(1); }
            100% { transform: translateY(20px) scale(1.05); }
        }

        /* Auth Card */
        .auth-card {
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 3rem 2.5rem;
            width: 100%;
            max-width: 420px;
            box-shadow: var(--glass-shadow);
            z-index: 1;
        }

        /* Logo Area */
        .logo-wrap {
            text-align: center;
            margin-bottom: 2rem;
        }
        .logo-wrap i.bi-key {
            font-size: 3rem;
            color: var(--primary-accent);
            background: rgba(255,255,255,0.9);
            border-radius: 50%;
            padding: 15px 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            display: inline-block;
            margin-bottom: 1rem;
        }
        .logo-wrap h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            letter-spacing: -0.5px;
        }
        .logo-wrap p {
            color: var(--text-muted);
            font-size: 0.875rem;
            margin-bottom: 0;
        }

        /* Form Inputs */
        .form-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-group-text {
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--glass-border);
            border-right: none;
            color: var(--text-muted);
        }

        .form-control {
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--glass-border);
            border-left: none;
            color: var(--text-main);
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
        }

        .form-control:focus {
            background: rgba(255,255,255,0.1);
            border-color: var(--primary-accent);
            color: var(--text-main);
            box-shadow: none;
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--primary-accent);
            color: var(--primary-accent);
        }

        /* Button */
        .btn-primary {
            background: var(--primary-accent);
            border: none;
            border-radius: 12px;
            padding: 0.875rem;
            font-weight: 600;
            width: 100%;
            margin-top: 1.5rem;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
        }

        /* Alerts */
        .alert-glass {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #fca5a5;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
    </style>
</head>
<body>

    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="auth-card">
        <div class="logo-wrap">
            <i class="bi bi-key"></i>
            <h1>Create New Password</h1>
            <p>Your new password must be different from previous used passwords.</p>
        </div>

        <?php if (!empty($error)): ?>
        <div class="alert-glass">
            <i class="bi bi-exclamation-circle-fill"></i>
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php endif; ?>

        <form action="/library_system/index.php?action=reset_password" method="POST">
            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Min. 8 characters" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Re-enter password" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
    </div>

</body>
</html>
