<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SkillShare — Permintaan</title>

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
                        body:    ['Inter', 'sans-serif'],
                        display: ['Instrument Serif', 'serif'],
                    },
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --background:        201 100% 8%;
            --foreground:        0 0% 100%;
            --muted-foreground:  240 4% 66%;
            --border:            0 0% 18%;
        }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0; min-height: 100vh; overflow-x: hidden;
            background: hsl(var(--background));
            color: hsl(var(--foreground));
            font-family: 'Inter', sans-serif;
        }
        [x-cloak] { display: none !important; }
        .font-display { font-family: 'Instrument Serif', serif; }

        .liquid-glass {
            background: rgba(255,255,255,0.01);
            backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);
            border: none; box-shadow: inset 0 1px 1px rgba(255,255,255,0.1);
            position: relative; overflow: hidden;
        }
        .liquid-glass::before {
            content: ''; position: absolute; inset: 0; border-radius: inherit; padding: 1.4px;
            background: linear-gradient(180deg,rgba(255,255,255,.45) 0%,rgba(255,255,255,.15) 20%,rgba(255,255,255,0) 40%,rgba(255,255,255,0) 60%,rgba(255,255,255,.15) 80%,rgba(255,255,255,.45) 100%);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor; mask-composite: exclude; pointer-events: none;
        }
        .surface      { background: rgba(10,17,23,0.68); border: 1px solid rgba(255,255,255,0.10); box-shadow: 0 20px 60px rgba(0,0,0,0.18); }
        .surface-soft { background: rgba(255,255,255,0.035); border: 1px solid rgba(255,255,255,0.08); }

        .dotted-divider {
            height: 1px;
            background-image: linear-gradient(to right, rgba(255,255,255,.24) 32%, rgba(255,255,255,0) 0%);
            background-size: 10px 1px; background-repeat: repeat-x;
        }

        .stars-bg { position: fixed; inset: 0; z-index: 0; pointer-events: none; overflow: hidden; }
        .star { position: absolute; background: white; border-radius: 50%; animation: twinkle var(--d) ease-in-out infinite var(--delay); }
        @keyframes twinkle {
            0%,100% { opacity: var(--lo); transform: scale(1); }
            50%      { opacity: var(--hi); transform: scale(1.5); }
        }
        @keyframes fade-rise {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-rise       { animation: fade-rise 0.7s ease-out both; }
        .animate-fade-rise-delay { animation: fade-rise 0.7s ease-out 0.15s both; }

        .xp-bar-track { background: rgba(255,255,255,0.08); border-radius: 99px; height: 4px; overflow: hidden; }
        .xp-bar-fill  { height: 100%; border-radius: 99px; background: rgba(255,255,255,0.55); }

        .badge-menunggu   { background: rgba(234,179,8,0.12);  color: #eab308; border: 1px solid rgba(234,179,8,0.3); }
        .badge-diterima   { background: rgba(34,197,94,0.12);  color: #22c55e; border: 1px solid rgba(34,197,94,0.3); }
        .badge-ditolak    { background: rgba(239,68,68,0.12);  color: #ef4444; border: 1px solid rgba(239,68,68,0.3); }
        .badge-dibatalkan { background: rgba(148,163,184,0.1); color: #94a3b8; border: 1px solid rgba(148,163,184,0.2); }

        .req-card { transition: border-color 0.2s, transform 0.2s; }
        .req-card:hover { border-color: rgba(255,255,255,0.18) !important; transform: translateY(-2px); }

        .section-box {
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 28px;
            padding: 24px;
            background: rgba(255,255,255,0.02);
        }
        .section-title {
            display: flex; align-items: center; gap: 12px;
            padding-bottom: 16px; margin-bottom: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }
    </style>
</head>
<body>
@php
    $authUser = auth()->user();
    $xpMeta   = $authUser->xpMeta();

    $makeInitials = function (?string $name): string {
        $parts = preg_split('/\s+/', trim((string)$name)) ?: [];
        $i = collect($parts)->filter()->take(2)->map(fn($p) => strtoupper(mb_substr($p,0,1)))->implode('');
        return $i ?: strtoupper(mb_substr((string)$name,0,1));
    };

    $photoUrl = function (?string $photo): ?string {
        if (!filled($photo)) return null;
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($photo)) return asset('storage/'.ltrim($photo,'/'));
        if (str_starts_with($photo,'http://') || str_starts_with($photo,'https://')) return $photo;
        return asset('storage/'.ltrim($photo,'/'));
    };

    $navInitials  = $makeInitials($authUser->name);
    $navPhoto     = $photoUrl($authUser->photo);
    $pendingCount = $incoming->where('status','menunggu')->count();
@endphp

<div class="stars-bg" id="stars-req" aria-hidden="true"></div>

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
                    <i class="{{ $xpMeta['badge']['icon'] }} text-[13px] text-white/80"></i>
                    <span class="text-muted-foreground">Lv.{{ $xpMeta['level'] }}</span>
                    <span class="mx-1 text-white/20">·</span>
                    <span>{{ number_format($xpMeta['xp']) }} XP</span>
                    <span class="ml-0.5 xp-bar-track w-10"><span class="xp-bar-fill" style="width:{{ $xpMeta['xp_progress'] }}%"></span></span>
                </div>
                <a href="{{ route('profile') }}" class="liquid-glass grid h-10 w-10 place-items-center overflow-hidden rounded-full text-sm font-medium text-foreground">
                    @if($navPhoto)
                        <img src="{{ $navPhoto }}" alt="{{ $authUser->name }}" class="h-full w-full object-cover">
                    @else
                        {{ $navInitials }}
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
    <main class="relative z-0 mx-auto max-w-3xl px-5 pb-24 pt-6 md:px-8 md:pt-10">

        @if(session('success'))
            <div class="mb-5 flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-5 py-3.5 text-sm animate-fade-rise">
                <i class="bi bi-check-circle text-white/60 shrink-0"></i>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-5 flex items-center gap-3 rounded-2xl border border-red-500/20 bg-red-500/10 px-5 py-3.5 text-sm text-red-300 animate-fade-rise">
                <i class="bi bi-exclamation-circle shrink-0"></i>
                {{ session('error') }}
            </div>
        @endif

        <section class="animate-fade-rise mb-10 flex flex-col items-center text-center">
            <p class="text-xs uppercase tracking-[0.22em] text-muted-foreground">Tukar Skill</p>
            <h1 class="font-display text-5xl mt-2 md:text-7xl">Permintaan Tukar Skill</h1>
        </section>

        {{-- INCOMING --}}
        <div class="animate-fade-rise section-box mb-6">
            <div class="section-title">
                <i class="bi bi-inbox text-xl text-white/50"></i>
                <h2 class="font-display text-3xl">Masuk</h2>
                <span class="inline-flex h-6 min-w-6 items-center justify-center rounded-full bg-white px-1.5 text-[11px] font-medium text-black">
                    {{ $incoming->count() }}
                </span>
                @if($pendingCount > 0)
                    <span class="ml-auto text-xs text-muted-foreground">{{ $pendingCount }} menunggu</span>
                @endif
            </div>

            @if($incoming->isEmpty())
                <div class="py-10 text-center">
                    <i class="bi bi-inbox text-3xl text-white/15 block mb-3"></i>
                    <p class="text-sm text-muted-foreground">Belum ada request masuk</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($incoming as $req)
                    @php
                        $sender      = $req->sender;
                        $senderInit  = $makeInitials($sender->name);
                        $senderPhoto = $photoUrl($sender->photo ?? null);
                        $badgeClass  = 'badge-'.$req->status;
                    @endphp
                    <div class="req-card surface-soft rounded-[20px] p-4">
                        <div class="flex items-center justify-between gap-3 mb-3">
                            <div class="flex items-center gap-3 min-w-0">
                                @if($senderPhoto)
                                    <img src="{{ $senderPhoto }}" alt="{{ $sender->name }}"
                                         class="h-10 w-10 shrink-0 rounded-full object-cover"
                                         style="border:1px solid rgba(255,255,255,0.1)">
                                @else
                                    <div class="grid h-10 w-10 shrink-0 place-items-center rounded-full font-display text-sm"
                                         style="background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.1)">
                                        {{ $senderInit }}
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="text-sm font-medium truncate">{{ $sender->name }}</p>
                                    <p class="text-xs text-muted-foreground truncate">{{ $sender->university ?? '' }}</p>
                                </div>
                            </div>
                            <span class="inline-flex shrink-0 rounded-full px-2.5 py-1 text-[11px] {{ $badgeClass }}">
                                {{ ucfirst($req->status) }}
                            </span>
                        </div>
                        <p class="text-xs text-muted-foreground mb-1">
                            Ingin belajar
                            <span class="inline-flex rounded-full bg-white px-2.5 py-0.5 text-[11px] font-medium text-black mx-1">
                                {{ $req->skill->name ?? '-' }}
                            </span>
                            darimu
                        </p>
                        <p class="text-[11px] text-muted-foreground mb-3">
                            <i class="bi bi-clock mr-1"></i>{{ $req->created_at->diffForHumans() }}
                        </p>
                        @if($req->status === 'menunggu')
                            <div class="grid grid-cols-2 gap-2 mt-3">
                                <form method="POST" action="{{ route('swap.reject', $req->id) }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full rounded-full px-3 py-2 text-xs text-muted-foreground transition hover:text-foreground"
                                            style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1)">
                                        <i class="bi bi-x mr-1"></i>Tolak
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('swap.accept', $req->id) }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full rounded-full bg-white px-3 py-2 text-xs font-medium text-black transition hover:scale-[1.02]">
                                        <i class="bi bi-check2 mr-1"></i>Terima
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- OUTGOING --}}
        <div class="animate-fade-rise-delay section-box">
            <div class="section-title">
                <i class="bi bi-send text-xl text-white/50"></i>
                <h2 class="font-display text-3xl">Terkirim</h2>
                <span class="inline-flex h-6 min-w-6 items-center justify-center rounded-full bg-white px-1.5 text-[11px] font-medium text-black">
                    {{ $outgoing->count() }}
                </span>
                <span class="ml-auto text-xs text-muted-foreground">
                    {{ $outgoing->where('status','menunggu')->count() }} menunggu
                </span>
            </div>

            @if($outgoing->isEmpty())
                <div class="py-10 text-center">
                    <i class="bi bi-send text-3xl text-white/15 block mb-3"></i>
                    <p class="text-sm text-muted-foreground">Belum ada request dikirim</p>
                    <a href="{{ route('dashboard') }}"
                       class="mt-3 inline-block text-xs text-muted-foreground underline underline-offset-2 hover:text-foreground">
                        Cari partner di Discover
                    </a>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($outgoing as $req)
                    @php
                        $receiver      = $req->receiver;
                        $receiverInit  = $makeInitials($receiver->name);
                        $receiverPhoto = $photoUrl($receiver->photo ?? null);
                        $badgeClass    = 'badge-'.$req->status;
                    @endphp
                    <div class="req-card surface-soft rounded-[20px] p-4">
                        <div class="flex items-center justify-between gap-3 mb-3">
                            <div class="flex items-center gap-3 min-w-0">
                                @if($receiverPhoto)
                                    <img src="{{ $receiverPhoto }}" alt="{{ $receiver->name }}"
                                         class="h-10 w-10 shrink-0 rounded-full object-cover"
                                         style="border:1px solid rgba(255,255,255,0.1)">
                                @else
                                    <div class="grid h-10 w-10 shrink-0 place-items-center rounded-full font-display text-sm"
                                         style="background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.1)">
                                        {{ $receiverInit }}
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="text-sm font-medium truncate">{{ $receiver->name }}</p>
                                    <p class="text-xs text-muted-foreground truncate">{{ $receiver->university ?? '' }}</p>
                                </div>
                            </div>
                            <span class="inline-flex shrink-0 rounded-full px-2.5 py-1 text-[11px] {{ $badgeClass }}">
                                {{ ucfirst($req->status) }}
                            </span>
                        </div>
                        <p class="text-xs text-muted-foreground mb-1">
                            Kamu ingin belajar
                            <span class="inline-flex rounded-full bg-white px-2.5 py-0.5 text-[11px] font-medium text-black mx-1">
                                {{ $req->skill->name ?? '-' }}
                            </span>
                        </p>
                        <p class="text-[11px] text-muted-foreground mb-3">
                            <i class="bi bi-clock mr-1"></i>{{ $req->created_at->diffForHumans() }}
                        </p>
                        @if($req->status === 'menunggu')
                            <form method="POST" action="{{ route('swap.cancel', $req->id) }}" class="mt-3">
                                @csrf
                                <button type="submit"
                                        class="w-full rounded-full px-3 py-2 text-xs text-muted-foreground transition hover:text-foreground"
                                        style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1)">
                                    <i class="bi bi-x mr-1"></i>Batalkan
                                </button>
                            </form>
                        @endif
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

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
    (function makeStars(id, count) {
        const c = document.getElementById(id);
        if (!c) return;
        for (let i = 0; i < count; i++) {
            const el = document.createElement('div');
            el.className = 'star';
            const s = Math.random() * 2.4 + 0.3;
            el.style.setProperty('--d',     (Math.random()*4+2).toFixed(1)+'s');
            el.style.setProperty('--delay', (Math.random()*6).toFixed(1)+'s');
            el.style.setProperty('--lo',    (Math.random()*.15+.05).toFixed(2));
            el.style.setProperty('--hi',    (Math.random()*.6+.4).toFixed(2));
            el.style.width  = s + 'px';
            el.style.height = s + 'px';
            el.style.left   = Math.random()*100 + '%';
            el.style.top    = Math.random()*100 + '%';
            c.appendChild(el);
        }
    })('stars-req', 260);
</script>
</body>
</html>
