<!DOCTYPE html>
<html lang="en" id="html-root">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Campus Store Management — Sangamner College</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        /* ── Variables ── */
        :root {
            --gold:   #c9a84c;
            --gold2:  #f0d080;
            --blue:   #1e3a8a;
            --blue2:  #3b82f6;
            --dark:   #0a0f1e;
            --text:   #f1f5f9;
            --muted:  rgba(241,245,249,0.65);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--dark);
            color: var(--text);
            overflow-x: hidden;
        }

        /* ════════════════════════════════════════
           HERO SECTION
        ════════════════════════════════════════ */
        #hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* College image background */
        .hero-bg {
            position: absolute;
            inset: 0;
            background-image: url('{{ asset("welcome-bg.png") }}');
            background-size: cover;
            background-position: center top;
            filter: brightness(0.45) saturate(0.8);
            transform: scale(1.04);
            transition: transform 8s ease;
            z-index: 0;
        }
        #hero:hover .hero-bg { transform: scale(1.00); }

        /* Deep gradient overlay */
        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                170deg,
                rgba(10,15,30,0.55) 0%,
                rgba(10,15,30,0.25) 40%,
                rgba(10,15,30,0.70) 75%,
                rgba(10,15,30,0.98) 100%
            );
            z-index: 1;
        }

        /* ── Navbar ── */
        .nav {
            position: relative; z-index: 10;
            display: flex; align-items: center; justify-content: space-between;
            padding: 22px 48px;
        }
        .nav-logo {
            display: flex; align-items: center; gap: 12px;
        }
        .nav-logo-badge {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--gold), var(--gold2));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }
        .nav-logo-badge svg { width: 20px; height: 20px; color: #0a0f1e; }
        .nav-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.15rem; font-weight: 700;
            color: #fff; letter-spacing: 0.01em;
        }
        .nav-brand span { color: var(--gold); }
        .nav-links {
            display: flex; align-items: center; gap: 12px;
        }
        .btn-outline {
            padding: 9px 22px; border-radius: 8px;
            border: 1.5px solid rgba(255,255,255,0.30);
            color: #fff; background: rgba(255,255,255,0.06);
            font-size: 0.875rem; font-weight: 500;
            text-decoration: none; cursor: pointer;
            backdrop-filter: blur(8px);
            transition: border-color 0.2s, background 0.2s, color 0.2s;
        }
        .btn-outline:hover {
            border-color: var(--gold);
            background: rgba(201,168,76,0.12);
            color: var(--gold2);
        }
        .btn-gold {
            padding: 9px 22px; border-radius: 8px;
            background: linear-gradient(135deg, var(--gold), #a8720e);
            color: #0a0f1e; font-size: 0.875rem; font-weight: 700;
            text-decoration: none; cursor: pointer; border: none;
            transition: opacity 0.2s, transform 0.2s;
            box-shadow: 0 4px 18px rgba(201,168,76,0.35);
        }
        .btn-gold:hover { opacity: 0.9; transform: translateY(-1px); }

        /* ── Hero Content ── */
        .hero-content {
            position: relative; z-index: 5;
            flex: 1;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            text-align: center;
            padding: 60px 24px 120px;
        }

        .hero-eyebrow {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(201,168,76,0.15);
            border: 1px solid rgba(201,168,76,0.35);
            border-radius: 100px;
            padding: 6px 16px;
            font-size: 0.78rem; font-weight: 600;
            letter-spacing: 0.12em; text-transform: uppercase;
            color: var(--gold2);
            margin-bottom: 28px;
            animation: fadeUp 0.7s ease both;
        }
        .hero-eyebrow::before {
            content: ''; width: 6px; height: 6px;
            background: var(--gold); border-radius: 50%;
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.8rem, 7vw, 5.5rem);
            font-weight: 900;
            line-height: 1.08;
            letter-spacing: -0.02em;
            color: #fff;
            margin-bottom: 10px;
            animation: fadeUp 0.7s 0.1s ease both;
        }
        .hero-title .accent { color: var(--gold); }

        .hero-subtitle {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.4rem, 3vw, 2.4rem);
            font-weight: 700;
            color: rgba(255,255,255,0.55);
            margin-bottom: 22px;
            animation: fadeUp 0.7s 0.18s ease both;
        }

        .hero-desc {
            max-width: 560px;
            font-size: 1.05rem;
            color: var(--muted);
            line-height: 1.7;
            margin-bottom: 44px;
            animation: fadeUp 0.7s 0.26s ease both;
        }

        .hero-cta {
            display: flex; align-items: center; gap: 14px; flex-wrap: wrap; justify-content: center;
            animation: fadeUp 0.7s 0.34s ease both;
        }

        .btn-primary {
            display: inline-flex; align-items: center; gap: 9px;
            padding: 14px 32px; border-radius: 10px;
            background: linear-gradient(135deg, #1d4ed8, #3b82f6);
            color: #fff; font-size: 1rem; font-weight: 600;
            text-decoration: none; border: none; cursor: pointer;
            box-shadow: 0 6px 24px rgba(59,130,246,0.4);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 32px rgba(59,130,246,0.5); }
        .btn-primary svg { width: 18px; height: 18px; }

        .btn-scroll {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 14px 28px; border-radius: 10px;
            border: 1.5px solid rgba(255,255,255,0.22);
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.85); font-size: 1rem; font-weight: 500;
            text-decoration: none; backdrop-filter: blur(8px);
            transition: border-color 0.2s, background 0.2s;
        }
        .btn-scroll:hover {
            border-color: rgba(255,255,255,0.5);
            background: rgba(255,255,255,0.10);
        }
        .btn-scroll svg { width: 16px; height: 16px; animation: bounce 1.8s infinite; }

        /* Stat strip */
        .hero-stats {
            position: absolute; bottom: 0; left: 0; right: 0; z-index: 5;
            display: flex; justify-content: center; gap: 0;
            animation: fadeUp 0.7s 0.44s ease both;
        }
        .stat-chip {
            padding: 16px 36px;
            background: rgba(10,15,30,0.72);
            backdrop-filter: blur(16px);
            border-top: 1px solid rgba(255,255,255,0.08);
            border-right: 1px solid rgba(255,255,255,0.06);
            text-align: center;
        }
        .stat-chip:first-child { border-left: 1px solid rgba(255,255,255,0.06); border-radius: 12px 0 0 0; }
        .stat-chip:last-child  { border-radius: 0 12px 0 0; }
        .stat-num {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem; font-weight: 900;
            color: var(--gold2);
            display: block; line-height: 1;
        }
        .stat-label {
            font-size: 0.72rem; letter-spacing: 0.08em;
            text-transform: uppercase; color: var(--muted);
            margin-top: 4px; display: block;
        }

        /* ════════════════════════════════════════
           FEATURES SECTION
        ════════════════════════════════════════ */
        #features {
            background: #0d1220;
            padding: 100px 48px;
        }

        .section-label {
            text-align: center;
            font-size: 0.72rem; font-weight: 700;
            letter-spacing: 0.14em; text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 14px;
        }
        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 900; text-align: center;
            color: #fff; margin-bottom: 16px;
            line-height: 1.15;
        }
        .section-desc {
            text-align: center; max-width: 520px;
            margin: 0 auto 64px;
            color: var(--muted); line-height: 1.7;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            max-width: 1100px; margin: 0 auto;
        }

        .feature-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 18px;
            padding: 36px 30px;
            position: relative; overflow: hidden;
            transition: border-color 0.25s, background 0.25s, transform 0.25s;
        }
        .feature-card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 2px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
            opacity: 0; transition: opacity 0.3s;
        }
        .feature-card:hover {
            border-color: rgba(201,168,76,0.25);
            background: rgba(255,255,255,0.055);
            transform: translateY(-4px);
        }
        .feature-card:hover::before { opacity: 1; }

        .feature-icon {
            width: 52px; height: 52px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 22px;
        }
        .feature-icon svg { width: 24px; height: 24px; }

        .feature-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem; font-weight: 700;
            color: #fff; margin-bottom: 10px;
        }
        .feature-text {
            font-size: 0.9rem; color: var(--muted);
            line-height: 1.7;
        }

        /* ════════════════════════════════════════
           WORKFLOW SECTION
        ════════════════════════════════════════ */
        #workflow {
            background: linear-gradient(135deg, #0a0f1e 0%, #0f1a35 100%);
            padding: 100px 48px;
        }

        .workflow-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px; align-items: center;
            max-width: 1100px; margin: 60px auto 0;
        }

        .workflow-steps { display: flex; flex-direction: column; gap: 6px; }

        .step {
            display: flex; gap: 18px; align-items: flex-start;
            padding: 20px 22px; border-radius: 14px;
            border: 1px solid transparent;
            transition: background 0.2s, border-color 0.2s;
            cursor: default;
        }
        .step:hover {
            background: rgba(255,255,255,0.04);
            border-color: rgba(255,255,255,0.08);
        }
        .step-num {
            width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0;
            background: linear-gradient(135deg, var(--blue), var(--blue2));
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 0.85rem; color: #fff;
        }
        .step-title { font-weight: 700; color: #fff; margin-bottom: 4px; }
        .step-desc  { font-size: 0.875rem; color: var(--muted); line-height: 1.6; }

        .workflow-visual {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 22px; padding: 40px 32px;
            display: flex; flex-direction: column; gap: 18px;
        }
        .wv-header {
            display: flex; align-items: center; gap: 8px;
            margin-bottom: 8px;
        }
        .wv-dot { width: 10px; height: 10px; border-radius: 50%; }
        .wv-title {
            font-family: 'Playfair Display', serif;
            font-size: 1rem; font-weight: 700; color: var(--gold2); margin-left: 4px;
        }
        .wv-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 13px 16px; border-radius: 10px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.06);
        }
        .wv-name { font-size: 0.875rem; font-weight: 600; color: #fff; }
        .wv-badge {
            font-size: 0.7rem; font-weight: 700; padding: 4px 10px;
            border-radius: 100px;
        }

        /* ════════════════════════════════════════
           LOGIN SECTION
        ════════════════════════════════════════ */
        #login {
            background: #0a0f1e;
            padding: 100px 48px 120px;
        }

        .roles-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            max-width: 900px; margin: 60px auto 0;
        }

        .role-card {
            position: relative;
            background: rgba(255,255,255,0.03);
            border: 1.5px solid rgba(255,255,255,0.08);
            border-radius: 20px; padding: 36px 28px;
            text-align: center;
            text-decoration: none; color: inherit;
            display: block;
            transition: transform 0.25s, border-color 0.25s, background 0.25s, box-shadow 0.25s;
            overflow: hidden;
        }
        .role-card::after {
            content: '';
            position: absolute; inset: 0;
            opacity: 0; transition: opacity 0.3s;
            border-radius: 20px;
        }
        .role-card:hover {
            transform: translateY(-6px);
            border-color: rgba(201,168,76,0.4);
            background: rgba(201,168,76,0.05);
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
        }

        .role-icon {
            width: 60px; height: 60px; border-radius: 16px;
            margin: 0 auto 18px;
            display: flex; align-items: center; justify-content: center;
        }
        .role-icon svg { width: 28px; height: 28px; }
        .role-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem; font-weight: 800;
            color: #fff; margin-bottom: 8px;
        }
        .role-desc { font-size: 0.85rem; color: var(--muted); line-height: 1.6; margin-bottom: 22px; }
        .role-btn {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 0.82rem; font-weight: 700;
            padding: 9px 20px; border-radius: 8px;
            transition: opacity 0.2s;
        }
        .role-btn:hover { opacity: 0.85; }
        .role-btn svg { width: 14px; height: 14px; }

        /* ════════════════════════════════════════
           FOOTER
        ════════════════════════════════════════ */
        footer {
            background: #070b16;
            border-top: 1px solid rgba(255,255,255,0.06);
            padding: 28px 48px;
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 12px;
        }
        footer p { font-size: 0.82rem; color: rgba(255,255,255,0.35); }
        footer span { color: var(--gold); }

        /* ════════════════════════════════════════
           SCROLL REVEAL
        ════════════════════════════════════════ */
        .reveal {
            opacity: 0; transform: translateY(28px);
            transition: opacity 0.65s ease, transform 0.65s ease;
        }
        .reveal.visible { opacity: 1; transform: translateY(0); }

        /* ════════════════════════════════════════
           ANIMATIONS
        ════════════════════════════════════════ */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(4px); }
        }

        /* ════════════════════════════════════════
           RESPONSIVE
        ════════════════════════════════════════ */
        @media (max-width: 900px) {
            .features-grid, .roles-grid { grid-template-columns: 1fr 1fr; }
            .workflow-grid { grid-template-columns: 1fr; }
            .nav { padding: 18px 24px; }
            #features, #workflow, #login { padding: 72px 24px; }
        }
        @media (max-width: 600px) {
            .features-grid, .roles-grid { grid-template-columns: 1fr; }
            .hero-stats { display: none; }
            .nav-brand { font-size: 0.95rem; }
        }
    </style>
</head>
<body>

<!-- ═══════════════════════════════════════════
     HERO
═══════════════════════════════════════════ -->
<section id="hero">
    <div class="hero-bg"></div>
    <div class="hero-overlay"></div>

    <!-- Navbar -->
    <nav class="nav">
        <div class="nav-logo">
            <div class="nav-logo-badge">
                <svg fill="currentColor" viewBox="0 0 24 24"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96C5 14.1 5.9 15 7 15h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63H17c.75 0 1.41-.41 1.75-1.03l3.58-6.49A1 1 0 0021 4H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
            </div>
            <span class="nav-brand">Campus <span>Store</span></span>
        </div>
        <div class="nav-links">
            <a href="#features" class="btn-outline">About</a>
            <a href="#login" class="btn-gold">Login</a>
        </div>
    </nav>

    <!-- Hero Content -->
    <div class="hero-content">
        <div class="hero-eyebrow">Sangamner College · Est. 1961</div>

        <h1 class="hero-title">
            Campus Store<br>
            <span class="accent">Management</span>
        </h1>
        <p class="hero-subtitle">Sangamner College, Sangamner</p>

        <p class="hero-desc">
            A unified platform for managing store requests, approvals, and supply operations — 
            connecting teachers, HODs, the Principal, and administrators seamlessly.
        </p>

        <div class="hero-cta">
            <a href="#login" class="btn-primary">
                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/></svg>
                Get Started
            </a>
            <a href="#features" class="btn-scroll">
                Learn More
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            </a>
        </div>
    </div>

    <!-- Stat Strip -->
    <div class="hero-stats">
        <div class="stat-chip">
            <span class="stat-num">6</span>
            <span class="stat-label">User Roles</span>
        </div>
        <div class="stat-chip">
            <span class="stat-num">5</span>
            <span class="stat-label">Approval Levels</span>
        </div>
        <div class="stat-chip">
            <span class="stat-num">100%</span>
            <span class="stat-label">Digital Workflow</span>
        </div>
        <div class="stat-chip">
            <span class="stat-num">Live</span>
            <span class="stat-label">Real-time Tracking</span>
        </div>
    </div>
</section>


<!-- ═══════════════════════════════════════════
     FEATURES
═══════════════════════════════════════════ -->
<section id="features">
    <p class="section-label reveal">What We Offer</p>
    <h2 class="section-title reveal">Built for the Entire Campus</h2>
    <p class="section-desc reveal">
        Every role in the college has a tailored dashboard and workflow — from classroom to administration.
    </p>

    <div class="features-grid">

        <div class="feature-card reveal">
            <div class="feature-icon" style="background:rgba(59,130,246,0.15);">
                <svg fill="currentColor" style="color:#60a5fa" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm4 18H6V4h7v5h5v11z"/></svg>
            </div>
            <h3 class="feature-title">Request Management</h3>
            <p class="feature-text">Teachers submit store requests digitally. Track every request from creation to delivery with full history and status updates.</p>
        </div>

        <div class="feature-card reveal">
            <div class="feature-icon" style="background:rgba(34,197,94,0.15);">
                <svg fill="currentColor" style="color:#4ade80" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
            </div>
            <h3 class="feature-title">Multi-Level Approvals</h3>
            <p class="feature-text">Structured workflow: Teacher → HOD → Principal → Trust Head → Admin. Each approver gets notified and can act immediately.</p>
        </div>

        <div class="feature-card reveal">
            <div class="feature-icon" style="background:rgba(201,168,76,0.15);">
                <svg fill="currentColor" style="color:#f0d080" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/></svg>
            </div>
            <h3 class="feature-title">Live Dashboard</h3>
            <p class="feature-text">Each role gets a personalised dashboard with metrics, charts, and pending items. Real-time data without page refresh.</p>
        </div>

        <div class="feature-card reveal">
            <div class="feature-icon" style="background:rgba(168,85,247,0.15);">
                <svg fill="currentColor" style="color:#c084fc" viewBox="0 0 24 24"><path d="M20 4H4v2l8 5 8-5V4zM4 13v7h16v-7l-8 5-8-5z"/></svg>
            </div>
            <h3 class="feature-title">Vendor & Supply</h3>
            <p class="feature-text">Providers receive approved orders, manage supply delivery, and mark fulfillment. Complete order-to-delivery tracking.</p>
        </div>

        <div class="feature-card reveal">
            <div class="feature-icon" style="background:rgba(249,115,22,0.15);">
                <svg fill="currentColor" style="color:#fb923c" viewBox="0 0 24 24"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63H17c.75 0 1.41-.41 1.75-1.03l3.58-6.49A1 1 0 0021 4H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
            </div>
            <h3 class="feature-title">Product Catalogue</h3>
            <p class="feature-text">Admins manage the approved products list with pricing. Teachers browse and select items when creating requests.</p>
        </div>

        <div class="feature-card reveal">
            <div class="feature-icon" style="background:rgba(239,68,68,0.15);">
                <svg fill="currentColor" style="color:#f87171" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 4l5 2.18V11c0 3.5-2.33 6.79-5 7.93C9.33 17.79 7 14.5 7 11V7.18L12 5z"/></svg>
            </div>
            <h3 class="feature-title">Role-Based Security</h3>
            <p class="feature-text">Each user sees only what they need. Strict access control ensures data privacy and workflow integrity at every level.</p>
        </div>

    </div>
</section>


<!-- ═══════════════════════════════════════════
     WORKFLOW
═══════════════════════════════════════════ -->
<section id="workflow">
    <p class="section-label reveal">How It Works</p>
    <h2 class="section-title reveal">From Request to Delivery</h2>

    <div class="workflow-grid">
        <div class="workflow-steps">
            <div class="step reveal">
                <div class="step-num">1</div>
                <div>
                    <p class="step-title">Teacher Creates Request</p>
                    <p class="step-desc">Selects items from the approved catalogue, adds quantities and purpose, and submits for approval.</p>
                </div>
            </div>
            <div class="step reveal">
                <div class="step-num">2</div>
                <div>
                    <p class="step-title">HOD Reviews & Approves</p>
                    <p class="step-desc">Department head verifies the request is appropriate and necessary before forwarding.</p>
                </div>
            </div>
            <div class="step reveal">
                <div class="step-num">3</div>
                <div>
                    <p class="step-title">Principal & Trust Authorise</p>
                    <p class="step-desc">Senior authority signs off on requests above thresholds, ensuring institutional accountability.</p>
                </div>
            </div>
            <div class="step reveal">
                <div class="step-num">4</div>
                <div>
                    <p class="step-title">Admin Processes Order</p>
                    <p class="step-desc">Admin assigns vendor, raises purchase order, and coordinates with the provider for fulfilment.</p>
                </div>
            </div>
            <div class="step reveal">
                <div class="step-num">5</div>
                <div>
                    <p class="step-title">Provider Delivers</p>
                    <p class="step-desc">Vendor supplies items and marks order complete. Teacher confirms receipt to close the loop.</p>
                </div>
            </div>
        </div>

        <div class="workflow-visual reveal">
            <div class="wv-header">
                <div class="wv-dot" style="background:#ef4444;"></div>
                <div class="wv-dot" style="background:#f59e0b;margin-left:6px;"></div>
                <div class="wv-dot" style="background:#22c55e;margin-left:6px;"></div>
                <span class="wv-title">Live Request — #REQ-047</span>
            </div>
            <div class="wv-row">
                <span class="wv-name">📝 Submitted by Teacher</span>
                <span class="wv-badge" style="background:rgba(34,197,94,0.15);color:#4ade80;">✓ Done</span>
            </div>
            <div class="wv-row">
                <span class="wv-name">🏫 HOD Approved</span>
                <span class="wv-badge" style="background:rgba(34,197,94,0.15);color:#4ade80;">✓ Done</span>
            </div>
            <div class="wv-row">
                <span class="wv-name">🎓 Principal Approved</span>
                <span class="wv-badge" style="background:rgba(34,197,94,0.15);color:#4ade80;">✓ Done</span>
            </div>
            <div class="wv-row">
                <span class="wv-name">⚡ Trust Head Review</span>
                <span class="wv-badge" style="background:rgba(245,158,11,0.15);color:#fbbf24;">● Active</span>
            </div>
            <div class="wv-row">
                <span class="wv-name">📦 Admin Processing</span>
                <span class="wv-badge" style="background:rgba(148,163,184,0.10);color:#94a3b8;">○ Waiting</span>
            </div>
            <div class="wv-row">
                <span class="wv-name">🚚 Provider Delivery</span>
                <span class="wv-badge" style="background:rgba(148,163,184,0.10);color:#94a3b8;">○ Waiting</span>
            </div>
        </div>
    </div>
</section>


<!-- ═══════════════════════════════════════════
     LOGIN / ROLE SELECTION
═══════════════════════════════════════════ -->
<section id="login">
    <p class="section-label reveal">Access Portal</p>
    <h2 class="section-title reveal">Choose Your Role</h2>
    <p class="section-desc reveal">
        Select your role to access your personalised dashboard. All data is secure and role-restricted.
    </p>

    <div class="roles-grid">

        <a href="{{ route('login') }}?role=teacher" class="role-card reveal">
            <div class="role-icon" style="background:rgba(34,197,94,0.15);">
                <svg fill="currentColor" style="color:#4ade80" viewBox="0 0 24 24"><path d="M12 3L1 9l4 2.18V15l7 4 7-4v-3.82L23 9zm0 2.27L19.52 9 12 12.73 4.48 9z"/></svg>
            </div>
            <p class="role-name">Teacher</p>
            <p class="role-desc">Submit and track your store requests. View approval status in real-time.</p>
            <span class="role-btn" style="background:rgba(34,197,94,0.15);color:#4ade80;">
                Login as Teacher
                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
            </span>
        </a>

        <a href="{{ route('login') }}?role=hod" class="role-card reveal">
            <div class="role-icon" style="background:rgba(99,102,241,0.15);">
                <svg fill="currentColor" style="color:#a5b4fc" viewBox="0 0 24 24"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
            </div>
            <p class="role-name">HOD</p>
            <p class="role-desc">Review and approve department requests. Monitor your team's activity.</p>
            <span class="role-btn" style="background:rgba(99,102,241,0.15);color:#a5b4fc;">
                Login as HOD
                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
            </span>
        </a>

        <a href="{{ route('login') }}?role=principal" class="role-card reveal">
            <div class="role-icon" style="background:rgba(245,158,11,0.15);">
                <svg fill="currentColor" style="color:#fbbf24" viewBox="0 0 24 24"><path d="M12 1l-9 4v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 4l5 2.18V11c0 3.5-2.33 6.79-5 7.93C9.33 17.79 7 14.5 7 11V7.18L12 5z"/></svg>
            </div>
            <p class="role-name">Principal</p>
            <p class="role-desc">Authorise high-value requests and oversee institutional procurement.</p>
            <span class="role-btn" style="background:rgba(245,158,11,0.15);color:#fbbf24;">
                Login as Principal
                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
            </span>
        </a>

        <a href="{{ route('login') }}?role=trust_head" class="role-card reveal">
            <div class="role-icon" style="background:rgba(168,85,247,0.15);">
                <svg fill="currentColor" style="color:#c084fc" viewBox="0 0 24 24"><path d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"/></svg>
            </div>
            <p class="role-name">Trust Head</p>
            <p class="role-desc">Final authority on major expenditures. Institution-wide financial oversight.</p>
            <span class="role-btn" style="background:rgba(168,85,247,0.15);color:#c084fc;">
                Login as Trust Head
                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
            </span>
        </a>

        <a href="{{ route('login') }}?role=provider" class="role-card reveal">
            <div class="role-icon" style="background:rgba(249,115,22,0.15);">
                <svg fill="currentColor" style="color:#fb923c" viewBox="0 0 24 24"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/></svg>
            </div>
            <p class="role-name">Provider</p>
            <p class="role-desc">Manage supply orders, fulfil deliveries, and update order completion status.</p>
            <span class="role-btn" style="background:rgba(249,115,22,0.15);color:#fb923c;">
                Login as Provider
                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
            </span>
        </a>

        <a href="{{ route('login') }}?role=admin" class="role-card reveal">
            <div class="role-icon" style="background:rgba(59,130,246,0.15);">
                <svg fill="currentColor" style="color:#60a5fa" viewBox="0 0 24 24"><path d="M12 15.5A3.5 3.5 0 018.5 12 3.5 3.5 0 0112 8.5a3.5 3.5 0 013.5 3.5 3.5 3.5 0 01-3.5 3.5m7.43-2.92c.04-.34.07-.68.07-1.08s-.03-.74-.07-1.08l2.32-1.81c.21-.16.27-.46.13-.7l-2.2-3.81c-.14-.24-.42-.32-.66-.24l-2.74 1.1c-.57-.44-1.18-.81-1.86-1.09l-.41-2.91C14.57 2.18 14.29 2 14 2h-4c-.29 0-.57.18-.62.46l-.41 2.91c-.68.28-1.29.65-1.86 1.09l-2.74-1.1c-.24-.08-.52 0-.66.24l-2.2 3.81c-.14.24-.08.54.13.7l2.32 1.81C4.03 11.26 4 11.6 4 12s.03.74.07 1.08L1.75 14.89c-.21.16-.27.46-.13.7l2.2 3.81c.14.24.42.32.66.24l2.74-1.1c.57.44 1.18.81 1.86 1.09l.41 2.91c.05.28.33.46.62.46h4c.29 0 .57-.18.62-.46l.41-2.91c.68-.28 1.29-.65 1.86-1.09l2.74 1.1c.24.08.52 0 .66-.24l2.2-3.81c.14-.24.08-.54-.13-.7l-2.32-1.81z"/></svg>
            </div>
            <p class="role-name">Admin</p>
            <p class="role-desc">Full system control — manage users, products, orders, and all reports.</p>
            <span class="role-btn" style="background:rgba(59,130,246,0.15);color:#60a5fa;">
                Login as Admin
                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
            </span>
        </a>

    </div>

    <!-- Single unified login link -->
    <p style="text-align:center;margin-top:36px;font-size:0.875rem;color:rgba(255,255,255,0.35);">
        Or use the unified login &nbsp;·&nbsp;
        <a href="{{ route('login') }}" style="color:var(--gold);text-decoration:none;font-weight:600;">Sign In →</a>
    </p>
</section>


<!-- ═══════════════════════════════════════════
     FOOTER
═══════════════════════════════════════════ -->
<footer>
    <p>© {{ date('Y') }} <span>Campus Store Management</span> · Sangamner College, Sangamner</p>
    <p>Built with ❤️ for <span>Sangamner College</span></p>
</footer>


<script>
    // Scroll reveal
    var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(e) {
            if (e.isIntersecting) {
                e.target.classList.add('visible');
                observer.unobserve(e.target);
            }
        });
    }, { threshold: 0.12 });

    document.querySelectorAll('.reveal').forEach(function(el, i) {
        el.style.transitionDelay = (i % 4) * 0.08 + 's';
        observer.observe(el);
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(function(a) {
        a.addEventListener('click', function(e) {
            var target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
</script>

</body>
</html>