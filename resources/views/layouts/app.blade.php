<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SkillShare')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        background: 'hsl(var(--background))',
                        foreground: 'hsl(var(--foreground))',
                        muted: 'hsl(var(--muted))',
                        'muted-foreground': 'hsl(var(--muted-foreground))',
                        primary: 'hsl(var(--primary))',
                        'primary-foreground': 'hsl(var(--primary-foreground))',
                        secondary: 'hsl(var(--secondary))',
                        accent: 'hsl(var(--accent))',
                        border: 'hsl(var(--border))',
                        input: 'hsl(var(--input))',
                    },
                    fontFamily: {
                        body: ['Inter', 'sans-serif'],
                        display: ['Instrument Serif', 'serif'],
                    },
                    maxWidth: {
                        '8xl': '88rem',
                    }
                }
            }
        }
    </script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        :root {
            --font-display: 'Instrument Serif', serif;
            --font-body: 'Inter', sans-serif;

            --background: 201 100% 8%;
            --foreground: 0 0% 100%;
            --muted-foreground: 240 4% 66%;
            --primary: 0 0% 100%;
            --primary-foreground: 0 0% 4%;
            --secondary: 0 0% 10%;
            --muted: 0 0% 10%;
            --accent: 0 0% 10%;
            --border: 0 0% 18%;
            --input: 0 0% 18%;
        }

        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }

        body {
            margin: 0;
            min-height: 100vh;
            overflow-x: hidden;
            background: hsl(var(--background));
            color: hsl(var(--foreground));
            font-family: var(--font-body);
        }

        [x-cloak] { display: none !important; }
        .font-display { font-family: var(--font-display); }

        .liquid-glass {
            background: rgba(255, 255, 255, 0.01);
            background-blend-mode: luminosity;
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            border: none;
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        .liquid-glass::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 1.4px;
            background: linear-gradient(
                180deg,
                rgba(255,255,255,0.45) 0%,
                rgba(255,255,255,0.15) 20%,
                rgba(255,255,255,0) 40%,
                rgba(255,255,255,0) 60%,
                rgba(255,255,255,0.15) 80%,
                rgba(255,255,255,0.45) 100%
            );
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }

        .surface {
            background: rgba(10, 17, 23, 0.68);
            border: 1px solid rgba(255,255,255,0.10);
            box-shadow: 0 20px 60px rgba(0,0,0,0.18);
        }

        .surface-soft {
            background: rgba(255,255,255,0.035);
            border: 1px solid rgba(255,255,255,0.08);
        }

        .dotted-divider {
            height: 1px;
            background-image: linear-gradient(to right, rgba(255,255,255,.24) 32%, rgba(255,255,255,0) 0%);
            background-position: top;
            background-size: 10px 1px;
            background-repeat: repeat-x;
        }

        @keyframes fade-rise {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-rise { animation: fade-rise 0.8s ease-out both; }
        .animate-fade-rise-delay { animation: fade-rise 0.8s ease-out 0.2s both; }
        .animate-fade-rise-delay-2 { animation: fade-rise 0.8s ease-out 0.4s both; }

        .stars-bg { position: fixed; inset: 0; z-index: 0; pointer-events: none; overflow: hidden; }
        .star { position: absolute; background: white; border-radius: 50%; animation: twinkle var(--d) ease-in-out infinite var(--delay); }

        @keyframes twinkle {
            0%, 100% { opacity: var(--lo); transform: scale(1); }
            50% { opacity: var(--hi); transform: scale(1.5); }
        }

        .scrollbar-hidden::-webkit-scrollbar { display: none; }
        .scrollbar-hidden { scrollbar-width: none; }

        input, textarea, select { color-scheme: dark; }
        input::placeholder, textarea::placeholder { color: hsl(var(--muted-foreground)); }
    </style>
</head>
<body>

<div class="stars-bg" id="stars-layout" aria-hidden="true"></div>

<div class="relative z-10 min-h-screen">
    <header class="relative z-10">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-5 py-5 md:px-8 md:py-6">
            <a href="{{ route('dashboard') }}" class="font-display text-3xl tracking-tight text-foreground">
                SkillShare
            </a>

            <div class="flex items-center gap-2.5">
                <a href="{{ route('dashboard') }}" class="text-sm text-muted-foreground transition-colors hover:text-foreground">Beranda</a>
                <a href="{{ route('profile') }}" class="liquid-glass ml-2 grid h-10 w-10 place-items-center rounded-full text-sm font-medium text-foreground" aria-label="Profil">
                    <i class="bi bi-person-circle"></i>
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="liquid-glass grid h-10 w-10 place-items-center rounded-full text-foreground" aria-label="Keluar">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </nav>
    </header>

    <main class="relative z-0 mx-auto max-w-7xl px-5 pb-24 pt-4 md:px-8">
        @if (session('success'))
            <div class="surface-soft mb-6 rounded-2xl px-5 py-4 text-sm text-foreground">
                <i class="bi bi-check-circle text-green-400"></i> {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide) lucide.createIcons();
    });

    function makeStars(containerId, count) {
        const container = document.getElementById(containerId);
        if (!container) return;

        for (let i = 0; i < count; i++) {
            const el = document.createElement('div');
            el.className = 'star';
            const size = Math.random() * 2.4 + 0.3;
            el.style.setProperty('--d', (Math.random() * 4 + 2).toFixed(1) + 's');
            el.style.setProperty('--delay', (Math.random() * 6).toFixed(1) + 's');
            el.style.setProperty('--lo', (Math.random() * 0.15 + 0.05).toFixed(2));
            el.style.setProperty('--hi', (Math.random() * 0.6 + 0.4).toFixed(2));
            el.style.width = size + 'px';
            el.style.height = size + 'px';
            el.style.left = Math.random() * 100 + '%';
            el.style.top = Math.random() * 100 + '%';
            container.appendChild(el);
        }
    }

    makeStars('stars-layout', 200);
</script>

@yield('scripts')
</body>
</html>
