<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning Resource Info System | Olivarez College</title>
    <!-- Google Fonts for Modern Typography -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        :root {
            /* Olivarez College Inspired Theme Colors */
            --oc-red: #A31D1D; /* Deep red */
            --oc-gold: #D4AF37; /* Metallic Gold */
            --oc-green: #1B5E20; /* Dark forest green */
            --text-light: #F4F6F8;
            --text-dark: #2D3748;
            --bg-overlay: rgba(163, 29, 29, 0.85); /* Red overlay */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            height: 100%;
            font-family: 'Inter', sans-serif;
            background-color: #0d3612;
            color: var(--text-dark);
            overflow: hidden;
        }

        /* Hero Section */
        .hero-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            background: linear-gradient(135deg, var(--oc-green) 0%, #0d3612 100%);
            color: var(--text-light);
            z-index: 1;
        }

        /* Decorative Background Elements */
        .hero-container::before {
            content: '';
            position: absolute;
            top: -10%;
            right: -5%;
            width: 50vw;
            height: 50vw;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.15) 0%, transparent 70%);
            z-index: -1;
        }

        .hero-container::after {
            content: '';
            position: absolute;
            bottom: -15%;
            left: -10%;
            width: 60vw;
            height: 60vw;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(27, 94, 32, 0.2) 0%, transparent 70%);
            z-index: -1;
        }

        /* Header / Navbar */
        header {
            padding: 2rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            animation: fadeInDown 1s ease forwards;
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo-placeholder {
            width: 50px;
            height: 50px;
            background-color: var(--oc-gold);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 800;
            color: var(--oc-green);
            font-size: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            border: 2px solid #fff;
        }

        .school-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: var(--oc-gold);
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }

        .school-sub {
            font-size: 0.85rem;
            font-weight: 300;
            opacity: 0.9;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 0 20px;
            animation: fadeInUp 1s ease 0.3s forwards;
            opacity: 0;
        }

        .badge {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--oc-gold);
            color: var(--oc-gold);
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 1.5rem;
            backdrop-filter: blur(5px);
        }

        h1.system-title {
            font-size: clamp(2.5rem, 5vw, 4.5rem);
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1rem;
            text-shadow: 2px 4px 10px rgba(0,0,0,0.3);
        }

        p.motto {
            font-size: clamp(1.1rem, 2vw, 1.5rem);
            font-weight: 300;
            max-width: 600px;
            margin: 0 auto 2.5rem;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.9);
            font-style: italic;
        }

        .accent-text {
            color: var(--oc-gold);
            font-weight: 600;
        }

        /* CTA Button */
        .btn-enter {
            display: inline-block;
            background-color: var(--oc-gold);
            color: var(--oc-green);
            text-decoration: none;
            padding: 15px 40px;
            border-radius: 30px;
            font-size: 1.1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
            border: 2px solid transparent;
        }

        .btn-enter:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 25px rgba(0,0,0,0.3);
            background-color: transparent;
            color: var(--oc-gold);
            border-color: var(--oc-gold);
        }

        .btn-enter::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300%;
            height: 300%;
            background-color: rgba(255,255,255,0.1);
            transform: translate(-50%, -50%) scale(0);
            border-radius: 50%;
            transition: transform 0.5s ease;
            z-index: 0;
        }

        .btn-enter:hover::before {
            transform: translate(-50%, -50%) scale(1);
        }

        .btn-enter span {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Decorative Line */
        .decor-lines {
            display: flex;
            gap: 10px;
            margin-top: 4rem;
        }
        
        .line {
            height: 4px;
            border-radius: 2px;
        }
        
        .line-red { width: 40px; background-color: var(--text-light); opacity: 0.5; }
        .line-gold { width: 80px; background-color: var(--oc-gold); }
        .line-green { width: 40px; background-color: var(--oc-green); }

        /* Footer */
        footer {
            padding: 2rem;
            text-align: center;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.6);
            background: rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        /* Animations */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            .logo-area {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

    <div class="hero-container">
        
        <!-- Header -->
        <header>
            <div class="logo-area">
                <div><img src="assets/images/olivarez_logo.png" alt="Olivarez College Logo" style="width: 60px; height: 60px; object-fit: contain; border-radius: 50%; border: 2px solid var(--oc-gold); box-shadow: 0 4px 15px rgba(0,0,0,0.2);"></div>
                <div>
                    <div class="school-name">Olivarez College</div>
                    <div class="school-sub">Parañaque City</div>
                </div>
            </div>
            <div style="font-size: 0.9rem; font-weight: 600; letter-spacing: 1px; color: var(--oc-gold);">
                EST. 1976
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <div class="badge">Official Portal</div>
            <h1 class="system-title">Learning Resource<br>Info System</h1>
            <p class="motto">"Educating the <span class="accent-text">Mind</span>, <span class="accent-text">Body</span> and <span class="accent-text">Soul</span>."</p>
            
            <a href="?action=login" class="btn-enter">
                <span>
                    Enter System Portal
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </span>
            </a>

            <!-- Decorative School Colors -->
            <div class="decor-lines">
                <div class="line line-red"></div>
                <div class="line line-gold"></div>
                <div class="line line-green"></div>
            </div>
        </main>

        <!-- Footer -->
        <footer>
            &copy; <?php echo date('Y'); ?> Olivarez College - Learning Resource Info System. All rights reserved.
        </footer>

    </div>

</body>
</html>
