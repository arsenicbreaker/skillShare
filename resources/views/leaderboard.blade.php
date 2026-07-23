<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillShare — Papan Peringkat</title>

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
                        'muted-foreground': 'hsl(var(--muted-foreground))',
                        border: 'hsl(var(--border))',
                    },
                    fontFamily: {
                        body: ['Inter', 'sans-serif'],
                        display: ['Instrument Serif', 'serif'],
                    },
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --background: 201 100% 8%;
            --foreground: 0 0% 100%;
            --muted-foreground: 240 4% 66%;
            --border: 0 0% 18%;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            background: hsl(var(--background));
            color: hsl(var(--foreground));
            font-family: 'Inter', sans-serif;
        }
        [x-cloak] { display: none !important; }
        .font-display { font-family: 'Instrument Serif', serif; }

        .liquid-glass {
            background: rgba(255,255,255,0.01);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            border: none;
            box-shadow: inset 0 1px 1px rgba(255,255,255,0.1);
            position: relative;
            overflow: hidden;
        }
        .liquid-glass::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 1.4px;
            background: linear-gradient(180deg,rgba(255,255,255,.45) 0%,rgba(255,255,255,.15) 20%,rgba(255,255,255,0) 40%,rgba(255,255,255,0) 60%,rgba(255,255,255,.15) 80%,rgba(255,255,255,.45) 100%);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }
        .surface {
            background: rgba(10,17,23,0.68);
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
            background-size: 10px 1px;
            background-repeat: repeat-x;
        }
        @keyframes fade-rise {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-rise         { animation: fade-rise 0.8s ease-out both; }
        .animate-fade-rise-delay   { animation: fade-rise 0.8s ease-out 0.15s both; }
        .animate-fade-rise-delay-2 { animation: fade-rise 0.8s ease-out 0.30s both; }

        .stars-bg { position: fixed; inset: 0; z-index: 0; pointer-events: none; overflow: hidden; }
        .star { position: absolute; background: white; border-radius: 50%; animation: twinkle var(--d) ease-in-out infinite var(--delay); }
        @keyframes twinkle {
            0%,100% { opacity: var(--lo); transform: scale(1); }
            50%      { opacity: var(--hi); transform: scale(1.5); }
        }

        .rank-badge {
            display: inline-flex; align-items: center; justify-content: center;
            width: 28px; height: 28px; border-radius: 50%;
            font-size: 13px; font-weight: 500; flex-shrink: 0;
        }
        .rank-1     { background: rgba(255,210,50,0.15); color: #ffd232; border: 1px solid rgba(255,210,50,0.3); }
        .rank-2     { background: rgba(192,192,215,0.15); color: #c0c0d7; border: 1px solid rgba(192,192,215,0.3); }
        .rank-3     { background: rgba(205,127,50,0.15); color: #cd7f32; border: 1px solid rgba(205,127,50,0.3); }
        .rank-other { background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.4); border: 1px solid rgba(255,255,255,0.08); font-size: 11px; }

        .top3-card { transition: transform 0.3s, border-color 0.3s; }
        .top3-card:hover { transform: translateY(-4px); border-color: rgba(255,255,255,0.2) !important; }

        .row-item { transition: background 0.2s, border-color 0.2s; }
        .row-item:hover { background: rgba(255,255,255,0.05) !important; border-color: rgba(255,255,255,0.15) !important; }

        .xp-bar-track { background: rgba(255,255,255,0.08); border-radius: 99px; height: 4px; overflow: hidden; }
        .xp-bar-fill  { height: 100%; border-radius: 99px; background: rgba(255,255,255,0.55); }
    </style>
</head>
<body>
@php
    $authUser = auth()->user();
    $myXpMeta = $authUser->xpMeta();

    $makeInitials = function(?string $name): string {
        $parts = preg_split('/\s+/', trim((string)$name)) ?: [];
        return collect($parts)->filter()->take(2)
            ->map(fn($p) => strtoupper(mb_substr($p, 0, 1)))->implode('');
    };

    $myInitials = $makeInitials($authUser->name);

    $rankIcons = [
        '<i class="bi bi-trophy-fill" style="color:#ffd232;font-size:1.1rem"></i>',
        '<i class="bi bi-trophy-fill" style="color:#c0c0d7;font-size:1.1rem"></i>',
        '<i class="bi bi-trophy-fill" style="color:#cd7f32;font-size:1.1rem"></i>',
    ];
    $rankClasses = ['rank-1','rank-2','rank-3'];
    $podiumOrder = [1, 0, 2];
@endphp

<div class="stars-bg" id="stars-lb" aria-hidden="true"></div>

<div x-data="{ logoutOpen: false }" class="relative z-10 min-h-screen">

    {{-- NAV --}}
    <header class="relative z-10">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-5 py-5 md:px-8 md:py-6">
            <a href="{{ route('dashboard') }}" class="font-display text-3xl tracking-tight text-foreground">SkillShare</a>

            <div class="hidden items-center gap-8 md:flex">
                <a href="{{ route('dashboard') }}"
                   class="text-sm transition-colors hover:text-foreground {{ request()->routeIs('dashboard') ? 'text-foreground' : 'text-muted-foreground' }}">
                    Beranda
                </a>
                <a href="{{ route('swap.index') }}"
                   class="text-sm transition-colors hover:text-foreground {{ request()->routeIs('swap*') ? 'text-foreground' : 'text-muted-foreground' }}">
                    Permintaan
                </a>
                <a href="{{ route('leaderboard') }}"
                   class="text-sm transition-colors hover:text-foreground {{ request()->routeIs('leaderboard') ? 'text-foreground' : 'text-muted-foreground' }}">
                    Papan Peringkat
                </a>
                <a href="{{ route('profile') }}"
                   class="text-sm transition-colors hover:text-foreground {{ request()->routeIs('profile*') ? 'text-foreground' : 'text-muted-foreground' }}">
                    Profil
                </a>
            </div>

            <div class="flex items-center gap-2.5">
                <div class="liquid-glass hidden h-10 items-center gap-2 rounded-full px-3.5 text-sm text-foreground sm:flex">
                    <i class="{{ $myXpMeta['badge']['icon'] }} text-[13px] text-white/80"></i>
                    <span class="text-muted-foreground">Lv.{{ $myXpMeta['level'] }}</span>
                    <span class="mx-1 text-white/20">·</span>
                    <span>{{ number_format($myXpMeta['xp']) }} XP</span>
                    <span class="ml-0.5 xp-bar-track w-10">
                        <span class="xp-bar-fill" style="width:{{ $myXpMeta['xp_progress'] }}%"></span>
                    </span>
                </div>
                <a href="{{ route('profile') }}"
                   class="liquid-glass grid h-10 w-10 place-items-center overflow-hidden rounded-full text-sm font-medium text-foreground">
                    @if($authUser->photo)
                        <img src="{{ asset('storage/'.$authUser->photo) }}" alt="{{ $authUser->name }}" class="h-full w-full object-cover">
                    @else
                        {{ $myInitials }}
                    @endif
                </a>
                <button type="button" @click="logoutOpen = true"
                        class="liquid-glass grid h-10 w-10 place-items-center rounded-full text-foreground">
                    <i class="bi bi-box-arrow-right text-base"></i>
                </button>
            </div>
        </nav>
    </header>

    {{-- MAIN --}}
    <main class="relative z-0 mx-auto max-w-7xl px-5 pb-24 pt-8 md:px-8 md:pt-14">

        {{-- HEADER --}}
        <section class="mb-10 md:mb-12 animate-fade-rise flex flex-col items-center text-center">
            <p class="text-xs uppercase tracking-[0.22em] text-muted-foreground">Minggu ini</p>
            <h1 class="font-display text-5xl md:text-6xl">Papan Peringkat <i class="bi bi-trophy-fill" style="color:#ffd232;font-size:3rem;vertical-align:middle"></i></h1>
            <p class="mt-4 max-w-xl text-base leading-7 text-muted-foreground">
                User paling aktif dan berprestasi di SkillShare. XP dihitung dari skill yang diajarkan, request diterima, dan sesi belajar selesai.
            </p>
        </section>

        {{-- MY RANK BANNER --}}
        <div class="animate-fade-rise-delay mb-8">
            <div class="rounded-[20px] flex items-center justify-between gap-4 px-5 py-4"
                 style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.15)">
                <div class="flex items-center gap-4">
                    <div class="grid h-10 w-10 place-items-center rounded-full font-display text-base"
                         style="background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.12)">
                        {{ $myInitials }}
                    </div>
                    <div>
                        <p class="text-sm font-medium">Posisimu saat ini</p>
                        <p class="text-xs mt-0.5 text-muted-foreground">{{ $authUser->university }} · {{ $authUser->major ?? '' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-5">
                    <div class="text-right">
                        <p class="text-xs text-muted-foreground">XP</p>
                        <p class="text-sm font-medium">{{ number_format($myXpMeta['xp']) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-muted-foreground">Rank</p>
                        <p class="text-sm font-medium">#{{ $myRank }}</p>
                    </div>
                    <span class="liquid-glass hidden h-9 items-center gap-1.5 rounded-full px-3 text-xs text-foreground sm:inline-flex">
                        <i class="{{ $myXpMeta['badge']['icon'] }} text-[11px] text-white/70"></i>
                        {{ $myXpMeta['badge']['nama'] }}
                    </span>
                </div>
            </div>
        </div>

        {{-- TOP 3 PODIUM --}}
        <section class="animate-fade-rise-delay-2 mb-8">
            <div class="grid gap-4 md:grid-cols-3">
                @php $top3 = $leaderboard->take(3); @endphp
                @foreach($podiumOrder as $i)
                    @php $entry = $top3[$i] ?? null; @endphp
                    @if($entry)
                    <div class="top3-card surface rounded-[28px] p-5 flex flex-col {{ $i !== 0 ? 'md:mt-6' : '' }}"
                         @if($i === 0) style="border-color:rgba(255,210,50,0.2)" @endif>

                        <div class="flex items-center justify-between gap-2 mb-5">
                            <span class="rank-badge {{ $rankClasses[$i] }}">{!! $rankIcons[$i] !!}</span>
                            <span class="liquid-glass inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs text-foreground">
                                <i class="{{ $entry['badge']['icon'] }} text-[11px] text-white/70"></i>
                                {{ $entry['badge']['nama'] }}
                            </span>
                        </div>

                        <div class="flex items-center gap-3 mb-4">
                            @if($entry['photo_url'])
                                <img src="{{ $entry['photo_url'] }}" alt="{{ $entry['name'] }}"
                                     class="h-14 w-14 shrink-0 rounded-full object-cover"
                                     style="border:1px solid rgba(255,255,255,0.12)">
                            @else
                                <div class="grid h-14 w-14 shrink-0 place-items-center rounded-full font-display text-xl"
                                     style="background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.12)">
                                    {{ $entry['initials'] }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="font-medium text-base truncate">{{ $entry['name'] }}</p>
                                <p class="text-xs mt-0.5 text-muted-foreground">{{ $entry['university'] }} · {{ $entry['major'] ?? '' }}</p>
                            </div>
                        </div>

                        <div class="dotted-divider my-3"></div>

                        <div class="flex items-center justify-between">
                            <span class="text-xs text-muted-foreground">XP minggu ini</span>
                            <span class="text-base font-medium">{{ number_format($entry['xp']) }}</span>
                        </div>
                        <div class="xp-bar-track mt-2">
                            <div class="xp-bar-fill" style="width:{{ $entry['xp_progress'] }}%"></div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </section>

        {{-- LIST #4 dst --}}
        <section class="animate-fade-rise-delay-2">
            <div class="dotted-divider mb-6"></div>
            <div class="space-y-2">
                @foreach($leaderboard->slice(3) as $index => $entry)
                <div class="row-item surface-soft rounded-2xl px-5 py-3.5 flex items-center gap-4">
                    <span class="rank-badge rank-other">#{{ $index + 4 }}</span>
                    @if($entry['photo_url'])
                        <img src="{{ $entry['photo_url'] }}" alt="{{ $entry['name'] }}"
                             class="h-9 w-9 shrink-0 rounded-full object-cover"
                             style="border:1px solid rgba(255,255,255,0.1)">
                    @else
                        <div class="grid h-9 w-9 shrink-0 place-items-center rounded-full font-display text-sm"
                             style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1)">
                            {{ $entry['initials'] }}
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate">{{ $entry['name'] }}</p>
                        <p class="text-xs text-muted-foreground">{{ $entry['university'] }} · {{ $entry['major'] ?? '' }}</p>
                    </div>
                    <span class="liquid-glass inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs text-foreground">
                        <i class="{{ $entry['badge']['icon'] }} text-[10px] text-white/70"></i>
                        {{ $entry['badge']['nama'] }}
                    </span>
                    <span class="text-sm font-medium tabular-nums">{{ number_format($entry['xp']) }} XP</span>
                </div>
                @endforeach
            </div>

            @if($myRank > 10)
            <div class="flex items-center justify-center gap-3 py-5">
                <span class="text-xs text-muted-foreground">#{{ $myRank - 1 }}</span>
                <div class="dotted-divider flex-1" style="max-width:40px;"></div>
                <span class="text-xs text-muted-foreground">···</span>
                <div class="dotted-divider flex-1" style="max-width:40px;"></div>
                <span class="text-xs text-muted-foreground">#{{ $myRank + 1 }}</span>
            </div>
            <div class="row-item rounded-2xl px-5 py-3.5 flex items-center gap-4"
                 style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.15)">
                <span class="rank-badge" style="background:rgba(255,255,255,0.1);color:#fff;border:1px solid rgba(255,255,255,0.2);font-size:11px;">#{{ $myRank }}</span>
                <div class="grid h-9 w-9 shrink-0 place-items-center rounded-full font-display text-sm"
                     style="background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2)">
                    {{ $myInitials }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium truncate">{{ $authUser->name }} <span class="text-muted-foreground font-normal">(kamu)</span></p>
                    <p class="text-xs text-muted-foreground">{{ $authUser->university }}</p>
                </div>
                <span class="liquid-glass inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs text-foreground">
                    <i class="{{ $myXpMeta['badge']['icon'] }} text-[10px] text-white/70"></i>
                    {{ $myXpMeta['badge']['nama'] }}
                </span>
                <span class="text-sm font-medium tabular-nums">{{ number_format($myXpMeta['xp']) }} XP</span>
            </div>
            @endif
        </section>

    </main>

    <footer class="relative z-0 border-t border-white/10 px-5 py-8 text-center text-sm text-muted-foreground">
        Built for students · Orbit Labs 2026
    </footer>

    {{-- MODAL LOGOUT --}}
    <div x-cloak x-show="logoutOpen" x-transition.opacity
         class="fixed inset-0 z-50 grid place-items-center bg-black/70 px-4"
         @keydown.escape.window="logoutOpen = false">
        <div @click.outside="logoutOpen = false" class="surface w-full max-w-md rounded-[30px] p-6 md:p-7">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="surface-soft grid h-12 w-12 shrink-0 place-items-center rounded-full">
                        <i class="bi bi-box-arrow-right text-xl text-muted-foreground"></i>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-muted-foreground">Keluar</p>
                        <h3 class="mt-2 font-display text-3xl md:text-4xl">Apakah kamu ingin keluar?</h3>
                        <p class="mt-3 text-sm leading-6 text-muted-foreground">Sesi kamu akan diakhiri.</p>
                    </div>
                </div>
                <button type="button" @click="logoutOpen = false"
                        class="surface-soft grid h-10 w-10 shrink-0 place-items-center rounded-full">
                    <i class="bi bi-x text-lg"></i>
                </button>
            </div>
            <div class="mt-7 grid grid-cols-2 gap-3">
                <button type="button" @click="logoutOpen = false"
                        class="surface-soft rounded-full px-5 py-3 text-sm text-muted-foreground transition hover:text-foreground">
                    Batal
                </button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex w-full items-center justify-center gap-2 rounded-full bg-white px-5 py-3 text-sm font-medium text-black transition hover:scale-[1.01]">
                        Ya, keluar <i class="bi bi-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
    function makeStars(id, count) {
        const c = document.getElementById(id);
        if (!c) return;
        for (let i = 0; i < count; i++) {
            const el = document.createElement('div');
            el.className = 'star';
            const s = Math.random() * 2.2 + 0.3;
            el.style.cssText = `--d:${(Math.random()*4+2).toFixed(1)}s;--delay:${(Math.random()*6).toFixed(1)}s;--lo:${(Math.random()*.15+.05).toFixed(2)};--hi:${(Math.random()*.6+.4).toFixed(2)};width:${s}px;height:${s}px;left:${Math.random()*100}%;top:${Math.random()*100}%`;
            c.appendChild(el);
        }
    }
    makeStars('stars-lb', 220);
</script>
</body>
</html>
