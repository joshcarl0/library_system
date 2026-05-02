<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password – Olivarez College</title>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        /* Shared Styles matching login.php */
        :root {
            --oc-green: #0d3612;
            --oc-gold: #d4af37;
            --glass-bg: rgba(255, 255, 255, 0.08);
            --glass-border: rgba(255, 255, 255, 0.1);
            --glass-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            --primary-accent: var(--oc-gold);
            --primary-hover: #b8962d;
            --text-main: #f8fafc;
            --text-muted: #cbd5e1;
            --error-color: #ef4444;
            --success-color: #10b981;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #051d08 0%, #0d3612 100%);
            color: var(--text-main);
            overflow: hidden;
            position: relative;
        }

        /* Animated Background Blobs */
        .blob {
            position: absolute;
            filter: blur(80px);
            z-index: -1;
            opacity: 0.3;
            animation: float 10s infinite ease-in-out alternate;
        }
        .blob-1 {
            width: 400px;
            height: 400px;
            background: var(--oc-gold);
            top: -100px;
            left: -100px;
            border-radius: 50%;
        }
        .blob-2 {
            width: 300px;
            height: 300px;
            background: #16a34a;
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
        .logo-wrap img {
            width: 85px;
            height: 85px;
            object-fit: contain;
            margin-bottom: 1.2rem;
            border-radius: 50%;
            background: #fff;
            padding: 8px;
            border: 3px solid var(--oc-gold);
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }
        .logo-wrap h1 {
            font-size: 1.6rem;
            font-weight: 800;
            margin-bottom: 0.25rem;
            letter-spacing: -0.5px;
            color: #fff;
        }
        .logo-wrap p {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 0;
            font-weight: 500;
        }

        /* Form Inputs */
        .form-label {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--text-muted);
            margin-bottom: 0.6rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .input-group-text {
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--glass-border);
            border-right: none;
            color: var(--oc-gold);
            border-radius: 12px 0 0 12px;
        }

        .form-control {
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--glass-border);
            border-left: none;
            color: var(--text-main);
            padding: 0.8rem 1rem;
            font-size: 0.95rem;
            border-radius: 0 12px 12px 0;
        }

        .form-control:focus {
            background: rgba(255,255,255,0.1);
            border-color: var(--oc-gold);
            color: var(--text-main);
            box-shadow: none;
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--oc-gold);
            color: var(--oc-gold);
        }

        /* Button */
        .btn-primary {
            background: var(--oc-gold);
            color: var(--oc-green);
            border: none;
            border-radius: 14px;
            padding: 0.9rem;
            font-weight: 800;
            width: 100%;
            margin-top: 1.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-primary:hover {
            background: #fff;
            color: var(--oc-green);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(212, 175, 55, 0.3);
        }

        /* Alerts */
        .alert-glass {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #fca5a5;
            padding: 0.8rem 1rem;
            border-radius: 14px;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        
        .alert-glass.success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: #6ee7b7;
        }

        .links-wrap {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.9rem;
        }

        .links-wrap a {
            color: var(--text-muted);
            text-decoration: none;
            transition: all 0.2s;
            font-weight: 600;
        }

        .links-wrap a:hover {
            color: var(--oc-gold);
        }
    </style>
</head>
<body>

    <!-- Animated blobs -->
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="auth-card">
        <div class="logo-wrap">
            <img src="/library_system/assets/images/olivarez_logo.png" alt="Olivarez College Logo">
            <h1>Forgot Password</h1>
            <p>Enter your registered email address</p>
        </div>

        <?php if (!empty($error)): ?>
        <div class="alert-glass">
            <i class="bi bi-exclamation-circle-fill"></i>
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php endif; ?>

        <form action="/library_system/index.php?action=forgot_password" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" id="email" name="email" class="form-control" placeholder="you@email.com" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Send OTP Code</button>
        </form>

        <div class="links-wrap">
            <a href="/library_system/index.php?action=login"><i class="bi bi-arrow-left"></i> Back to Login</a>
        </div>
    </div>

</body>
</html>
