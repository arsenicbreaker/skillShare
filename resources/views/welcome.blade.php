<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SkillShare</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>✦</text></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css'])
    <style>
        :root {
            --bg: hsl(201, 100%, 8%);
            --bg2: hsl(201, 100%, 10%);
            --bg3: hsl(201, 100%, 6%);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            min-height: 100vh;
            overflow-x: hidden;
            background: var(--bg);
            color: white;
            font-family: 'Inter', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.1);
            position: relative;
        }

        .glass::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 1px;
            background: linear-gradient(160deg, rgba(255,255,255,0.3) 0%, rgba(255,255,255,0.05) 40%, rgba(255,255,255,0.0) 60%, rgba(255,255,255,0.15) 100%);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }

        #navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            display: flex;
            justify-content: center;
            padding: 24px 32px;
            transition: padding 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #navbar .pill {
            width: 100%;
            max-width: 1200px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 8px;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #navbar.scrolled {
            padding: 12px 32px;
        }

        #navbar.scrolled .pill {
            max-width: 980px;
            background: rgba(10, 30, 45, 0.5);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 9999px;
            padding: 10px 36px;
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.25);
        }


        #navbar.scrolled .logo {
            font-size: 1.2rem;
        }

        #navbar.scrolled .nav-link {
            font-size: 0.78rem;
        }

        #navbar.scrolled .nbtn {
            padding: 7px 16px;
            font-size: 0.78rem;
        }

        .logo {
            font-family: 'Instrument Serif', serif;
            font-size: 1.75rem;
            color: white;
            text-decoration: none;
            transition: font-size 0.5s;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-link {
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.65);
            text-decoration: none;
            transition: color 0.2s, font-size 0.5s;
        }

        .nav-link:hover,
        .nav-link.active {
            color: white;
        }

        .nav-btns {
            display: flex;
            gap: 10px;
        }

        .nbtn {
            padding: 9px 22px;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
        }

        .nbtn-outline {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: white;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 0 12px rgba(255, 255, 255, 0.15), inset 0 1px 1px rgba(255, 255, 255, 0.1);
        }

        .nbtn-outline:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .nbtn-solid {
            background: white;
            border: 1px solid white;
            color: black;
        }

        .nbtn-solid:hover {
            background: rgba(255, 255, 255, 0.88);
        }

        .star {
            position: absolute;
            background: white;
            border-radius: 50%;
            animation: twinkle var(--d) ease-in-out infinite var(--delay);
        }

        @keyframes twinkle {
            0%, 100% {
                opacity: var(--lo);
                transform: scale(1);
            }
            50% {
                opacity: var(--hi);
                transform: scale(1.5);
            }
        }

        .stars-bg {
            position: absolute;
            inset: 0;
            z-index: 1;
            pointer-events: none;
            overflow: hidden;
        }

        #hero {
            position: relative;
            min-height: 100vh;
            overflow: hidden;
            background: hsl(201, 100%, 7%);
        }

        .hero-video {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.55;
            z-index: 0;
        }

        .badge-float {
            position: absolute;
            z-index: 5;
            border-radius: 16px;
            padding: 10px 16px;
        }

        .badge-title {
            font-size: 13px;
            font-weight: 600;
            margin: 0;
            white-space: nowrap;
        }

        .badge-sub {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.5);
            margin: 3px 0 0;
            white-space: nowrap;
        }

        .badge-float-1 {
            left: 3%;
            top: 22%;
            animation: drift-a 5.2s ease-in-out infinite;
        }

        .badge-float-2 {
            right: 3%;
            top: 22%;
            animation: drift-b 6.1s ease-in-out infinite 0.7s;
        }

        .badge-float-3 {
            left: 4%;
            top: 46%;
            animation: drift-c 4.8s ease-in-out infinite 1.3s;
        }

        .badge-float-4 {
            right: 4%;
            top: 46%;
            animation: drift-d 5.7s ease-in-out infinite 2s;
        }

        .badge-float-5 {
            left: 3%;
            bottom: 20%;
            animation: drift-e 4.5s ease-in-out infinite 0.4s;
        }

        .badge-float-6 {
            right: 3%;
            bottom: 20%;
            animation: drift-a 5.2s ease-in-out infinite 1s;
        }

        @keyframes drift-a {
            0%, 100% { transform: translate(0px, 0px) rotate(-1.5deg); }
            50% { transform: translate(8px, -14px) rotate(-2deg); }
        }

        @keyframes drift-b {
            0%, 100% { transform: translate(0px, 0px) rotate(2deg); }
            50% { transform: translate(-10px, -10px) rotate(2.5deg); }
        }

        @keyframes drift-c {
            0%, 100% { transform: translate(0px, 0px) rotate(-0.5deg); }
            50% { transform: translate(6px, -12px) rotate(0.5deg); }
        }

        @keyframes drift-d {
            0%, 100% { transform: translate(0px, 0px) rotate(1deg); }
            50% { transform: translate(-8px, -12px) rotate(2deg); }
        }

        @keyframes drift-e {
            0%, 100% { transform: translate(0px, 0px) rotate(-2deg); }
            50% { transform: translate(8px, -10px) rotate(-1deg); }
        }

        .hero-content {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
            padding: 0 1.5rem;
        }

        .hero-title {
            font-family: 'Instrument Serif', serif;
            font-size: clamp(2.2rem, 5.5vw, 4.5rem);
            font-weight: 400;
            line-height: 1.1;
            letter-spacing: -1.5px;
            max-width: 1100px;
        }

        .hero-title-accent {
            color: rgba(96, 165, 250, 0.9);
            text-shadow:
                0 0 40px rgba(59, 130, 246, 0.5),
                0 0 80px rgba(59, 130, 246, 0.2);
        }

        .hero-sub {
            margin: 1.75rem 0 0;
            max-width: 500px;
            font-size: 1rem;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.8);
        }

        .hero-btns {
            margin-top: 2.5rem;
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            justify-content: center;
        }

       .btn-primary {
            background: rgba(96, 165, 250, 0.2);
            border: 1px solid rgba(96, 165, 250, 0.5);
            color: white;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 14px 38px;
            border-radius: 9999px;
            text-decoration: none;
            transition: all 0.2s;
            box-shadow: 0 0 20px rgba(96, 165, 250, 0.2);
        }

        .btn-primary:hover {
            background: rgba(96, 165, 250, 0.3);
            box-shadow: 0 0 30px rgba(96, 165, 250, 0.3);
        }

        .btn-glass {
            color: white;
            font-weight: 500;
            font-size: 0.95rem;
            padding: 14px 38px;
            border-radius: 9999px;
            text-decoration: none;
            transition: opacity 0.2s;
        }

        .btn-glass:hover {
            opacity: 0.75;
        }

        #cara-kerja {
            background: var(--bg2);
            padding: 6rem 2rem;
            position: relative;
            overflow: hidden;
        }

        .section-stars {
            position: absolute;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .section-title {
            font-family: 'Instrument Serif', serif;
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 400;
            text-align: center;
            margin-bottom: 3.5rem;
            position: relative;
            z-index: 2;
        }

        .cara-kerja-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            max-width: 1300px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .step-card {
            border-radius: 24px;
            padding: 2.25rem;
            transition: transform 0.3s;
            position: relative;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            overflow: hidden;
        }

        .step-card:hover {
            transform: translateY(-4px);
        }

        .step-card::before {
            display: block;
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 1px;
            background: linear-gradient(
                180deg,
                rgba(255,255,255,0.25) 0%,
                rgba(255,255,255,0.05) 40%,
                rgba(255,255,255,0.0) 60%,
                rgba(255,255,255,0.1) 100%
            );
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }

        .step-card-1, .step-card-2, .step-card-3 {
            background: transparent;
            border: none;
            box-shadow: none;
        }


        .step-num {
            font-family: 'Instrument Serif', serif;
            font-size: 3.5rem;
            line-height: 1;
            margin-bottom: 1.25rem;
        }

        .step-num-1, .step-num-2, .step-num-3 { 
            color: rgba(255, 255, 255, 0.6); 
        }

        

        .step-title {
            font-size: 1.1rem;
            font-weight: 500;
            margin-bottom: 0.75rem;
        }

        .step-desc {
            font-size: 0.875rem;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.52);
        }

        #skill {
            background: var(--bg);
            padding: 5rem 0;
            overflow: hidden;
            position: relative;
        }

        .skill-title {
            font-family: 'Instrument Serif', serif;
            font-size: clamp(1.8rem, 4vw, 3rem);
            font-weight: 400;
            text-align: center;
            margin-bottom: 3rem;
            padding: 0 2rem;
            position: relative;
            z-index: 2;
        }

        .marquee-wrap {
            overflow: hidden;
            position: relative;
            z-index: 2;
            margin-bottom: 12px;
        }

        .marquee-track {
            display: flex;
            width: max-content;
        }

        .marquee-track.go-left {
            animation: ml 35s linear infinite;
        }

        .marquee-track.go-right {
            animation: mr 35s linear infinite;
        }

        .marquee-track:hover {
            animation-play-state: paused;
        }

        @keyframes ml {
            from { transform: translateX(0); }
            to { transform: translateX(-50%); }
        }

        @keyframes mr {
            from { transform: translateX(-50%); }
            to { transform: translateX(0); }
        }

        .skill-pill {
            display: inline-flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.08);
            border-radius: 9999px;
            padding: 9px 22px;
            font-size: 0.875rem;
            white-space: nowrap;
            margin: 0 8px;
            flex-shrink: 0;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        #testimoni {
            background: var(--bg2);
            padding: 6rem 2rem;
            position: relative;
            overflow: hidden;
        }

        .testimoni-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            max-width: 1300px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .testi-card {
            border-radius: 20px;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            background: transparent;
            border: none;
            box-shadow: none;
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
            position: relative;
            overflow: hidden;
        }

        .testi-card::before {
            display: block;
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 1px;
            background: linear-gradient(
                180deg,
                rgba(255,255,255,0.25) 0%,
                rgba(255,255,255,0.05) 40%,
                rgba(255,255,255,0.0) 60%,
                rgba(255,255,255,0.1) 100%
            );
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }

        .testi-stars {
            color: #facc15;
            font-size: 0.875rem;
            letter-spacing: 2px;
        }

        .testi-quote {
            font-size: 0.9rem;
            font-style: italic;
            line-height: 1.75;
            color: rgba(255, 255, 255, 0.58);
            flex: 1;
        }

        .testi-footer {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-top: 0.75rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .testi-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
            border: 2px solid rgba(255, 255, 255, 0.15);
        }

        .testi-name {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .testi-univ {
            font-size: 0.78rem;
            color: rgba(255, 255, 255, 0.42);
        }

        #cta {
            background: var(--bg);
            padding: 7rem 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .cta-card {
            border-radius: 32px;
            padding: 4rem 5rem;
            max-width: 1000px;
            width: 100%;
            text-align: center;
            position: relative;
            z-index: 2;
            background: transparent;
            border: none;
            box-shadow: none;
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
        }

        .cta-card::before {
            display: block;
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 1px;
            background: linear-gradient(
                180deg,
                rgba(255,255,255,0.25) 0%,
                rgba(255,255,255,0.05) 40%,
                rgba(255,255,255,0.0) 60%,
                rgba(255,255,255,0.1) 100%
            );
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }


        .cta-title {
            font-family: 'Instrument Serif', serif;
            font-size: clamp(2rem, 5vw, 3.2rem);
            font-weight: 400;
            line-height: 1.15;
            margin-bottom: 1rem;
        }

        .cta-sub {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.5);
            margin-bottom: 2.5rem;
        }

        .cta-btn {
            display: inline-block;
            background: white;
            color: black;
            font-weight: 500;
            font-size: 0.95rem;
            padding: 15px 44px;
            border-radius: 9999px;
            text-decoration: none;
            transition: opacity 0.2s;
        }

        .cta-btn:hover {
            opacity: 0.88;
        }

        footer {
            background: var(--bg3);
            border-top: 1px solid rgba(255, 255, 255, 0.07);
            padding: 2rem;
            text-align: center;
        }

        .footer-text {
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.3);
        }

        @keyframes fade-rise {
            from {
                opacity: 0;
                transform: translateY(28px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .ar  {
            animation: fade-rise 0.9s ease-out both; 
        }
        .ar1 { 
            animation: fade-rise 0.9s ease-out 0.15s both; 
        }
        .ar2 { 
            animation: fade-rise 0.9s ease-out 0.3s both; 
        }
        .ar3 { 
            animation: fade-rise 0.9s ease-out 0.45s both; 
        }

        /* Tampilan mobile */
        @media (max-width: 768px) {
        #navbar {
            padding: 12px 16px;
        }

        #navbar .pill {
            flex-wrap: wrap;
            gap: 8px;
            padding: 0 4px;
        }

        #navbar.scrolled .pill {
            padding: 8px 16px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .logo {
            font-size: 1.3rem;
        }

        .nav-links {
            display: flex;
            order: 3;
            width: 100%;
            justify-content: center;
            gap: 1rem;
            padding: 6px 0 2px;
            border-top: 1px solid rgba(255,255,255,0.08);
        }

        .nav-link {
            font-size: 0.75rem;
        }

        .nav-btns {
            order: 2;
        }

        .nbtn {
            padding: 6px 12px;
            font-size: 0.75rem;
        }

        .badge-float {
            padding: 7px 10px;
            border-radius: 12px;
        }

        .badge-title {
            font-size: 11px;
        }

        .badge-sub {
            font-size: 9px;
        }

        .badge-float-1 { left: 1%; top: 16%; 
        }
        .badge-float-3 { 
            left: 1%; top: 42%; 
        }
        .badge-float-5 { 
            left: 1%; bottom: 14%; 
        }
        .badge-float-2 { 
            right: 1%; top: 16%; 
        }
        .badge-float-4 { 
            right: 1%; top: 42%; 
        }
        .badge-float-6 { 
            right: 1%; bottom: 14%; 
        }

        .hero-content {
            padding: 0 90px;
        }

        .hero-title {
            font-size: clamp(1.6rem, 6vw, 2.5rem);
            letter-spacing: -0.5px;
        }

        .hero-sub {
            font-size: 0.85rem;
        }

        .hero-btns {
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .btn-primary, .btn-glass {
            width: 100%;
            max-width: 220px;
            text-align: center;
            padding: 12px 20px;
            font-size: 0.85rem;
        }

        .cara-kerja-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
            padding: 0 1rem;
        }

        .step-card {
            padding: 1.75rem;
        }

        .testimoni-grid {
            grid-template-columns: 1fr;
            padding: 0 0.5rem;
        }

        .cta-card {
            padding: 2.5rem 1.5rem;
        }

        .cta-title {
            font-size: clamp(1.6rem, 6vw, 2.5rem);
        }
    }
    </style>
</head>
<body>

<nav id="navbar">
    <div class="pill">
        <a href="#" class="logo">SkillShare</a>
        <div class="nav-links">
            <a href="#" class="nav-link active">Beranda</a>
            <a href="#cara-kerja" class="nav-link">Cara kerja</a>
            <a href="#skill" class="nav-link">Skill</a>
            <a href="#testimoni" class="nav-link">Testimoni</a>
        </div>
        <div class="nav-btns">
            <a href="{{ route('login') }}" class="nbtn nbtn-outline">Masuk</a>
            <a href="{{ route('register') }}" class="nbtn nbtn-solid">Daftar</a>
        </div>
    </div>
</nav>

<div id="hero">
    <video class="hero-video" autoplay loop muted playsinline aria-hidden="true">
        <source src="https://d8j0ntlcm91z4.cloudfront.net/user_38xzZboKViGWJOttwIXH07lWA1P/hf_20260314_131748_f2ca2a28-fed7-44c8-b9a9-bd9acdd5ec31.mp4" type="video/mp4">
    </video>

    <div class="stars-bg" id="stars-hero"></div>

    <div class="badge-float glass badge-float-1">
        <p class="badge-title"><i class="bi bi-star-fill" style="color:#facc15"></i> +50 XP</p>
        <p class="badge-sub">Profil dibuat!</p>
    </div>
    <div class="badge-float glass badge-float-2">
        <p class="badge-title"><i class="bi bi-crosshair" style="color:#60a5fa"></i> 94% match</p>
        <p class="badge-sub">Python ⇄ Figma</p>
    </div>
    <div class="badge-float glass badge-float-3">
        <p class="badge-title"><i class="bi bi-check-circle-fill" style="color:#34d399"></i> 85% Match sukses</p>
    </div>
    <div class="badge-float glass badge-float-4">
        <p class="badge-title"><i class="bi bi-fire" style="color:#fb923c"></i> 500+ Skill</p>
    </div>
    <div class="badge-float glass badge-float-5">
        <p class="badge-title"><i class="bi bi-trophy-fill" style="color:#facc15"></i> Level 3 unlocked!</p>
    </div>
    <div class="badge-float glass badge-float-6">
        <p class="badge-title"><i class="bi bi-envelope-fill" style="color:#a78bfa"></i> 1000+</p>
        <p class="badge-sub">Request diterima</p>
    </div>

    <div class="hero-content">
        <h1 class="hero-title ar">
            Punya skill?<br>
            Tukar dengan sesama <span class="hero-title-accent">mahasiswa</span>
        </h1>
        <p class="hero-sub ar1">
            Bagikan skillmu, dapat skill baru. Gratis. Khusus mahasiswa.
        </p>
        <div class="hero-btns ar2">
            <a href="{{ route('register') }}" class="btn-primary">Mulai sekarang</a>
            <a href="{{ route('login') }}" class="btn-glass glass">Masuk</a>
        </div>
    </div>
</div>

<section id="cara-kerja">
    <div class="section-stars" id="stars-cara"></div>
    <h2 class="section-title">Cara kerja</h2>
    <div class="cara-kerja-grid">
        <div class="step-card step-card-1">
            <p class="step-num step-num-1">01</p>
            <h3 class="step-title">Daftarkan skillmu</h3>
            <p class="step-desc">Isi skill yang kamu punya dan mau dipelajari. Profil kamu jadi kartu identitas belajarmu.</p>
        </div>
        <div class="step-card step-card-2">
            <p class="step-num step-num-2">02</p>
            <h3 class="step-title">Temukan partner</h3>
            <p class="step-desc">Kirim request ke user yang cocok denganmu berdasarkan kecocokan skill dan lokasi.</p>
        </div>
        <div class="step-card step-card-3">
            <p class="step-num step-num-3">03</p>
            <h3 class="step-title">Terima & naik level</h3>
            <p class="step-desc">Makin aktif makin tinggi levelmu dan makin dipercaya oleh komunitas SkillShare.</p>
        </div>
    </div>
</section>

<section id="skill">
    <div class="section-stars" id="stars-skill"></div>
    <h2 class="skill-title">Skill apa yang mau kamu pelajari?</h2>

    @php
    $rows = [
        ['dir' => 'left',  'skills' => ['Laravel','React','Python','Figma','Flutter','MySQL','JavaScript','TypeScript','Node.js','Vue.js','Go','Rust','Swift','Kotlin','Django']],
        ['dir' => 'right', 'skills' => ['Firebase','PHP','UI/UX','Canva','Premiere Pro','Photoshop','Illustrator','Blender','After Effects','CorelDraw','Sketch','InVision','Framer','Adobe XD','Webflow']],
        ['dir' => 'left',  'skills' => ['Digital Marketing','English','Data Analysis','Machine Learning','AWS','Docker','Kotlin','Swift','Unity','Game Design','TensorFlow','PyTorch','Tableau','PowerBI','Excel']],
        ['dir' => 'right', 'skills' => ['Public Speaking','Copywriting','Video Editing','Content Creation','SEO','Social Media','Email Marketing','Branding','Business Analysis','Project Management','SCRUM','Product Design','Wireframing','Storyboarding','Pitch Deck']],
        ['dir' => 'left',  'skills' => ['R','MATLAB','Jupyter','Pandas','NumPy','Scikit-learn','OpenCV','Hadoop','Spark','SQL','PostgreSQL','MongoDB','Redis','GraphQL','REST API']],
    ];
    @endphp

    @foreach($rows as $row)
    <div class="marquee-wrap">
        <div class="marquee-track go-{{ $row['dir'] }}">
            @php $doubled = array_merge($row['skills'], $row['skills']); @endphp
            @foreach($doubled as $sk)
            <span class="skill-pill">{{ $sk }}</span>
            @endforeach
        </div>
    </div>
    @endforeach
</section>

<section id="testimoni">
    <div class="section-stars" id="stars-testi"></div>
    <h2 class="section-title">Apa kata mereka?</h2>
    <div class="testimoni-grid">
        <div class="testi-card">
            <div class="testi-stars">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
            </div>
            <p class="testi-quote">"Gara-gara SkillShare aku nemu partner Laravel dalam 2 hari. Sekarang udah level 3!"</p>
            <div class="testi-footer">
                <img class="testi-avatar" src="{{ asset('img/avatar-1.jpg') }}" alt="Budi Santoso">
                <div>
                    <p class="testi-name">Budi Santoso</p>
                    <p class="testi-univ">Universitas Airlangga · Informatika</p>
                </div>
            </div>
        </div>
        <div class="testi-card">
            <div class="testi-stars">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
            </div>
            <p class="testi-quote">"Cuma 2 hari udah nemu partner belajar Figma. Portofolioku makin keren dan profesional!"</p>
            <div class="testi-footer">
                <img class="testi-avatar" src="{{ asset('img/avatar-2.jpg') }}" alt="Sari Rahayu">
                <div>
                    <p class="testi-name">Sari Rahayu</p>
                    <p class="testi-univ">Universitas Indonesia · Desain Produk</p>
                </div>
            </div>
        </div>
        <div class="testi-card">
            <div class="testi-stars">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
            </div>
            <p class="testi-quote">"Skill Python aku makin bagus setelah ketemu partner dari SkillShare. Sangat recommended!"</p>
            <div class="testi-footer">
                <img class="testi-avatar" src="{{ asset('img/avatar-3.jpg') }}" alt="Andi Wijaya">
                <div>
                    <p class="testi-name">Andi Wijaya</p>
                    <p class="testi-univ">Universitas Brawijaya · Teknik Informatika</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="cta">
    <div class="section-stars" id="stars-cta"></div>
    <div class="cta-card">
        <h2 class="cta-title">Sudah 120+ mahasiswa<br>saling berbagi skill.</h2>
        <p class="cta-sub">Dan berkembang bersama. Kamu kapan?</p>
        <a href="{{ route('register') }}" class="cta-btn">Daftar sekarang</a>
    </div>
</section>

<footer>
    <p class="footer-text">Built for students &middot; Made With Orbit Labs</p>
</footer>

<script>
function makeStars(containerId, count) {
    const c = document.getElementById(containerId);
    if (!c) return;
    for (let i = 0; i < count; i++) {
        const el = document.createElement('div');
        el.className = 'star';
        const sz = Math.random() * 2.4 + 0.3;
        el.style.setProperty('--d', (Math.random() * 4 + 2).toFixed(1) + 's');
        el.style.setProperty('--delay', (Math.random() * 6).toFixed(1) + 's');
        el.style.setProperty('--lo', (Math.random() * 0.15 + 0.05).toFixed(2));
        el.style.setProperty('--hi', (Math.random() * 0.6 + 0.4).toFixed(2));
        el.style.width = sz + 'px';
        el.style.height = sz + 'px';
        el.style.left = Math.random() * 100 + '%';
        el.style.top = Math.random() * 100 + '%';
        c.appendChild(el);
    }
}

makeStars('stars-hero', 280);
makeStars('stars-cara', 120);
makeStars('stars-skill', 80);
makeStars('stars-testi', 120);
makeStars('stars-cta', 100);

(function() {
    const nav = document.getElementById('navbar');
    let last = false;
    function check() {
        const scrolled = window.scrollY > 70;
        if (scrolled !== last) {
            nav.classList.toggle('scrolled', scrolled);
            last = scrolled;
        }
    }
    window.addEventListener('scroll', check, { passive: true });
})();

(function() {
    const sections = [document.getElementById('hero'), ...document.querySelectorAll('section[id]')];
    const navLinks = document.querySelectorAll('.nav-link');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            navLinks.forEach(link => link.classList.remove('active'));
            if (entry.target.id === 'hero') {
                document.querySelector('.nav-link[href="#"]').classList.add('active');
            } else {
                const match = document.querySelector(`.nav-link[href="#${entry.target.id}"]`);
                if (match) match.classList.add('active');
            }
        });
    }, { threshold: 0.4 });

    sections.forEach(s => { if (s) observer.observe(s); });
})();
</script>
</body>
</html>