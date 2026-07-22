<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SkillShare — Discover</title>

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

        .stars-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
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

        .scrollbar-hidden::-webkit-scrollbar { display: none; }
        .scrollbar-hidden { scrollbar-width: none; }
    </style>
</head>
<body>
@php
    $authUser = auth()->user();

    $makeInitials = function (?string $name): string {
        $nameParts = preg_split('/\s+/', trim((string) $name)) ?: [];
        $initials = collect($nameParts)
            ->filter()
            ->take(2)
            ->map(fn ($part) => strtoupper(mb_substr($part, 0, 1)))
            ->implode('');

        return $initials !== ''
            ? $initials
            : strtoupper(mb_substr((string) $name, 0, 1));
    };

    $photoUrl = function (?string $photo): ?string {
        if (! filled($photo)) {
            return null;
        }

        // Path di DB: photos/xxx.png → public via storage link
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($photo)) {
            return asset('storage/' . ltrim($photo, '/'));
        }

        // Fallback kalau path sudah full URL / relative public
        if (str_starts_with($photo, 'http://') || str_starts_with($photo, 'https://')) {
            return $photo;
        }

        return asset('storage/' . ltrim($photo, '/'));
    };

    $xpMeta = $authUser->xpMeta();

    $user = [
        'name' => $authUser->name,
        'initials' => $makeInitials($authUser->name),
        'photo_url' => $photoUrl($authUser->photo),
        'xp' => $xpMeta['xp'],
        'level' => $xpMeta['level'],
        'badge' => $xpMeta['badge'],
        'xp_progress' => $xpMeta['xp_progress'],
        'next_level' => $xpMeta['next_level'],
        'next_level_xp' => $xpMeta['next_level_xp'],
        'is_max_level' => $xpMeta['is_max_level'],
        'university' => $authUser->university ?? '',
        'city' => $authUser->city ?? '',
    ];

    $formatPartner = function ($partnerUser) use ($authUser, $makeInitials, $photoUrl) {
        $teachSkills = $partnerUser->userSkills
            ->where('type', 'ajarkan')
            ->map(fn ($us) => $us->skill)
            ->filter();

        $primarySkill = $teachSkills->first();
        $skillNames = $teachSkills->pluck('name')->filter()->values()->all();
        $categoryNames = $teachSkills
            ->map(fn ($skill) => $skill?->category?->name)
            ->filter()
            ->unique()
            ->values()
            ->all();

        $partnerXp = $partnerUser->xpMeta();

        return [
            'id' => $partnerUser->id,
            'name' => $partnerUser->name,
            'initials' => $makeInitials($partnerUser->name),
            'photo_url' => $photoUrl($partnerUser->photo),
            'university' => $partnerUser->university ?: 'Kampus belum diisi',
            'city' => $partnerUser->city ?: '',
            'bio' => $partnerUser->bio ?: 'Belum ada bio.',
            'skill' => $primarySkill?->name ?? 'Belum ada skill',
            'skill_id' => $primarySkill?->id,
            'skills' => $skillNames,
            'skills_text' => strtolower(implode(' ', $skillNames)),
            'category' => $primarySkill?->category?->name ?? '',
            'categories' => $categoryNames,
            'categories_text' => implode(',', $categoryNames),
            'match' => (int) ($partnerUser->match_percent ?? 0),
            'level' => $partnerXp['level'],
            'badge' => $partnerXp['badge'],
            'xp' => $partnerXp['xp'],
            'same_campus' => filled($authUser->university)
                && filled($partnerUser->university)
                && strcasecmp($partnerUser->university, $authUser->university) === 0,
        ];
    };
@endphp

<div class="stars-bg" id="stars-dashboard" aria-hidden="true"></div>

<div
    x-data="skillShareApp()"
    x-init="$nextTick(() => lucide.createIcons())"
    class="relative z-10 min-h-screen"
>
    <header class="relative z-10">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-5 py-5 md:px-8 md:py-6">
            <a href="#" class="font-display text-3xl tracking-tight text-foreground">
                SkillShare<sup class="text-xs"></sup>
            </a>

            <div class="hidden items-center gap-8 md:flex">
                <a href="{{ route('dashboard') }}"
                    class="text-sm transition-colors hover:text-foreground {{ request()->routeIs('dashboard') ? 'text-foreground' : 'text-muted-foreground' }}">
                    Discover
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
                {{-- XP / Level / Badge — acuan User::updateLevel() & getBadgeAttribute() --}}
                <div
                    class="liquid-glass hidden h-10 items-center gap-2 rounded-full px-3.5 text-sm text-foreground sm:flex"
                    :title="xpMeta.badge.nama + ' · Lv. ' + xpMeta.level + ' · ' + Number(xpMeta.xp).toLocaleString('id-ID') + ' XP' + (xpMeta.is_max_level ? '' : ' · menuju Lv. ' + xpMeta.next_level)"
                >
                    <i class="text-[13px] text-white/80" :class="xpMeta.badge.icon"></i>
                    <span class="whitespace-nowrap">
                        <span class="text-muted-foreground">Lv.<span x-text="xpMeta.level"></span></span>
                        <span class="mx-1 text-white/20">·</span>
                        <span x-text="Number(xpMeta.xp).toLocaleString('id-ID')"></span> XP
                    </span>
                    <span class="ml-0.5 h-1 w-10 overflow-hidden rounded-full bg-white/10" aria-hidden="true">
                        <span
                            class="block h-full rounded-full bg-white/55 transition-all duration-500"
                            :style="'width: ' + xpMeta.xp_progress + '%'"
                        ></span>
                    </span>
                </div>

                <button type="button" id="profile" class="liquid-glass grid h-10 w-10 place-items-center overflow-hidden rounded-full text-sm font-medium text-foreground" aria-label="Profil">
                    @if ($user['photo_url'])
                        <img
                            src="{{ $user['photo_url'] }}"
                            alt="{{ $user['name'] }}"
                            class="h-full w-full object-cover"
                        >
                    @else
                        {{ $user['initials'] }}
                    @endif
                </button>

                <button type="button" @click="logoutOpen = true; $nextTick(() => lucide.createIcons())" class="liquid-glass grid h-10 w-10 place-items-center rounded-full text-foreground" aria-label="Keluar">
                    <i data-lucide="log-out" class="h-4 w-4"></i>
                </button>
            </div>
        </nav>
    </header>

    <main id="discover" class="relative z-0 mx-auto max-w-7xl px-5 pb-24 pt-8 md:px-8 md:pt-14">
        <section class="mb-6 md:mb-8">
            <h1 class="animate-fade-rise font-display text-5xl leading-[0.95] tracking-tight md:text-7xl">
                Selamat datang, {{ $user['name'] }}! <span class="font-body text-4xl md:text-5xl">👋</span>
            </h1>
            <p class="animate-fade-rise-delay mt-5 max-w-2xl text-base leading-7 text-muted-foreground md:text-lg">
                Ini partner yang cocok buat kamu hari ini. Temukan orang baru, tukar skill, dan tumbuh bersama.
            </p>

            <div class="animate-fade-rise-delay-2 mt-8 grid gap-3 md:grid-cols-[1fr_auto_auto]">
                <label class="surface-soft flex h-12 items-center gap-3 rounded-2xl px-4">
                    <i data-lucide="search" class="h-4 w-4 text-muted-foreground"></i>
                    <input
                        x-model.debounce.200ms="query"
                        type="search"
                        placeholder="Cari nama, skill, atau kampus..."
                        class="w-full bg-transparent text-sm text-foreground outline-none placeholder:text-muted-foreground"
                    >
                </label>

                <select x-model="category" class="surface-soft h-12 rounded-2xl px-4 text-sm text-foreground outline-none">
                    <option value="Semua" class="bg-[#061f2b]">Semua kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->name }}" class="bg-[#061f2b]">{{ $cat->name }}</option>
                    @endforeach
                </select>

                <button
                    type="button"
                    @click="toggleSort()"
                    class="surface-soft h-12 rounded-2xl px-5 text-sm text-foreground inline-flex items-center gap-2 transition hover:border-white/20"
                    :title="sortDescending ? 'Match tertinggi dulu' : 'Match terendah dulu'"
                >
                    <i data-lucide="arrow-up-down" class="h-4 w-4"></i>
                    <span x-text="sortDescending ? 'Urutkan' : 'Urutkan'"></span>
                </button>
            </div>
        </section>

        <section class="pb-12 md:pb-16">
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3" data-partner-grid>
                @forelse ($users as $partnerUser)
                    @php $partner = $formatPartner($partnerUser); @endphp
                    <article
                        data-name="{{ strtolower($partner['name']) }}"
                        data-skill="{{ $partner['skills_text'] }}"
                        data-category="{{ $partner['categories_text'] }}"
                        data-university-name="{{ strtolower($partner['university']) }}"
                        data-university="{{ $partner['same_campus'] ? 'same' : 'different' }}"
                        data-match="{{ $partner['match'] }}"
                        x-show="partnerVisible($el.dataset)"
                        x-transition.opacity.duration.200ms
                        class="surface group rounded-[28px] p-5 transition duration-300 hover:-translate-y-1 hover:border-white/20"
                    >
                        <div class="flex items-center justify-between gap-4">
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full border border-white/10 px-3 py-1 text-xs text-muted-foreground"
                                title="{{ $partner['badge']['nama'] }} · {{ number_format($partner['xp']) }} XP"
                            >
                                <i class="{{ $partner['badge']['icon'] }} text-[11px] text-white/70"></i>
                                Lv.{{ $partner['level'] }}
                            </span>
                            @if ($partner['city'])
                                <span class="text-xs text-muted-foreground">{{ $partner['city'] }}</span>
                            @endif
                        </div>

                        <div class="mt-6 flex items-center gap-4">
                            @if ($partner['photo_url'])
                                <img
                                    src="{{ $partner['photo_url'] }}"
                                    alt="{{ $partner['name'] }}"
                                    class="h-14 w-14 shrink-0 rounded-full border border-white/10 object-cover"
                                >
                            @else
                                <div class="grid h-14 w-14 shrink-0 place-items-center rounded-full border border-white/10 bg-white/5 font-display text-xl">
                                    {{ $partner['initials'] }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <h3 class="truncate text-lg font-medium">{{ $partner['name'] }}</h3>
                                @if ($partner['category'])
                                    <p class="text-sm text-muted-foreground">{{ $partner['category'] }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="mt-5 flex items-start gap-2 text-sm text-muted-foreground">
                            <i data-lucide="graduation-cap" class="mt-0.5 h-4 w-4 shrink-0"></i>
                            <span>{{ $partner['university'] }}</span>
                        </div>

                        <div class="mt-5">
                            <p class="text-xs uppercase tracking-[0.18em] text-muted-foreground">Dia ajarkan</p>
                            <div class="mt-2 flex flex-wrap gap-2">
                                @forelse ($partner['skills'] as $skillName)
                                    <span class="inline-flex rounded-full bg-white px-3 py-1 text-xs font-medium text-black">{{ $skillName }}</span>
                                @empty
                                    <span class="inline-flex rounded-full border border-white/10 px-3 py-1 text-xs text-muted-foreground">Belum ada skill</span>
                                @endforelse
                            </div>
                        </div>

                        <p class="mt-5 min-h-16 text-sm leading-6 text-muted-foreground">{{ $partner['bio'] }}</p>

                        <button
                            type="button"
                            @click="openSwap({ id: {{ $partner['id'] }}, name: @js($partner['name']), skill: @js($partner['skill']), skill_id: {{ $partner['skill_id'] ? (int) $partner['skill_id'] : 'null' }} })"
                            class="liquid-glass mt-6 flex w-full items-center justify-between rounded-full px-5 py-3 text-sm text-foreground transition duration-300 hover:scale-[1.02]"
                        >
                            <span>+ Kirim request</span>
                            <i data-lucide="arrow-right" class="h-4 w-4"></i>
                        </button>
                    </article>
                @empty
                    <div class="surface col-span-full rounded-[28px] px-6 py-12 text-center">
                        <p class="font-display text-3xl">Belum ada partner</p>
                        <p class="mt-3 text-sm text-muted-foreground">User lain yang sudah daftar dan selesai onboarding akan muncul di sini.</p>
                    </div>
                @endforelse
            </div>

            @if ($users->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $users->links() }}
                </div>
            @endif
        </section>

        @if ($campusUsers->isNotEmpty())
            <div class="dotted-divider"></div>

            <section class="py-12 md:py-16">
                <div class="mb-7">
                    <p class="text-xs uppercase tracking-[0.22em] text-muted-foreground">Di dekatmu</p>
                    <h2 class="mt-2 font-display text-4xl tracking-tight md:text-5xl">Mahasiswa dari kampus terdekat</h2>
                    <p class="mt-2 text-sm text-muted-foreground">Mahasiswa terdekat di sekitarmu.</p>
                </div>

                <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3" data-partner-grid>
                    @foreach ($campusUsers as $partnerUser)
                        @php $partner = $formatPartner($partnerUser); @endphp
                        <article
                            data-name="{{ strtolower($partner['name']) }}"
                            data-skill="{{ $partner['skills_text'] }}"
                            data-category="{{ $partner['categories_text'] }}"
                            data-university-name="{{ strtolower($partner['university']) }}"
                            data-university="same"
                            data-match="{{ $partner['match'] }}"
                            x-show="partnerVisible($el.dataset)"
                            x-transition.opacity.duration.200ms
                            class="surface group rounded-[28px] p-5 transition duration-300 hover:-translate-y-1 hover:border-white/20"
                        >
                            <div class="flex items-center justify-between gap-4">
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full border border-white/10 px-3 py-1 text-xs text-muted-foreground"
                                    title="{{ $partner['badge']['nama'] }} · {{ number_format($partner['xp']) }} XP"
                                >
                                    <i class="{{ $partner['badge']['icon'] }} text-[11px] text-white/70"></i>
                                    Lv.{{ $partner['level'] }}
                                </span>
                                @if ($partner['city'])
                                    <span class="text-xs text-muted-foreground">{{ $partner['city'] }}</span>
                                @endif
                            </div>

                            <div class="mt-6 flex items-center gap-4">
                                @if ($partner['photo_url'])
                                    <img
                                        src="{{ $partner['photo_url'] }}"
                                        alt="{{ $partner['name'] }}"
                                        class="h-14 w-14 shrink-0 rounded-full border border-white/10 object-cover"
                                    >
                                @else
                                    <div class="grid h-14 w-14 shrink-0 place-items-center rounded-full border border-white/10 bg-white/5 font-display text-xl">
                                        {{ $partner['initials'] }}
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <h3 class="truncate text-lg font-medium">{{ $partner['name'] }}</h3>
                                    @if ($partner['category'])
                                        <p class="text-sm text-muted-foreground">{{ $partner['category'] }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-5 flex items-start gap-2 text-sm text-muted-foreground">
                                <i data-lucide="graduation-cap" class="mt-0.5 h-4 w-4 shrink-0"></i>
                                <span>{{ $partner['university'] }}</span>
                            </div>

                            <div class="mt-5">
                                <p class="text-xs uppercase tracking-[0.18em] text-muted-foreground">Dia ajarkan</p>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @forelse ($partner['skills'] as $skillName)
                                        <span class="inline-flex rounded-full bg-white px-3 py-1 text-xs font-medium text-black">{{ $skillName }}</span>
                                    @empty
                                        <span class="inline-flex rounded-full border border-white/10 px-3 py-1 text-xs text-muted-foreground">Belum ada skill</span>
                                    @endforelse
                                </div>
                            </div>

                            <p class="mt-5 min-h-16 text-sm leading-6 text-muted-foreground">{{ $partner['bio'] }}</p>

                            <button
                                type="button"
                                @click="openSwap({ id: {{ $partner['id'] }}, name: @js($partner['name']), skill: @js($partner['skill']), skill_id: {{ $partner['skill_id'] ? (int) $partner['skill_id'] : 'null' }} })"
                                class="liquid-glass mt-6 flex w-full items-center justify-between rounded-full px-5 py-3 text-sm text-foreground transition duration-300 hover:scale-[1.02]"
                            >
                                <span>+ Kirim request</span>
                                <i data-lucide="arrow-right" class="h-4 w-4"></i>
                            </button>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </main>

    <footer class="relative z-0 border-t border-white/10 px-5 py-8 text-center text-sm text-muted-foreground">
        Built for students · Orbit Labs 2026
    </footer>

    <!-- Modal kirim swap request -->
    <div x-cloak x-show="swapOpen" x-transition.opacity class="fixed inset-0 z-50 grid place-items-center bg-black/70 px-4" @keydown.escape.window="swapOpen = false">
        <div @click.outside="swapOpen = false" class="surface w-full max-w-lg rounded-[30px] p-6 md:p-7">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-muted-foreground">Swap request</p>
                    <h3 class="mt-2 font-display text-4xl">Belajar bersama <span x-text="selectedPartner.name"></span></h3>
                </div>
                <button type="button" @click="swapOpen = false" class="surface-soft grid h-10 w-10 place-items-center rounded-full">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>
            </div>

            <div class="mt-6 space-y-4">
                <div>
                    <label class="mb-2 block text-sm text-muted-foreground">Skill yang kamu tawarkan</label>
                    <select x-model="swapForm.offerSkill" class="surface-soft h-12 w-full rounded-2xl px-4 text-sm text-foreground outline-none">
                        @forelse (($myTeachSkills ?? collect()) as $skillName)
                            <option value="{{ $skillName }}" class="bg-[#061f2b]">{{ $skillName }}</option>
                        @empty
                            <option value="" class="bg-[#061f2b]">Belum ada skill yang kamu ajarkan</option>
                        @endforelse
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm text-muted-foreground">Skill yang ingin dipelajari</label>
                    <input x-model="selectedPartner.skill" readonly class="surface-soft h-12 w-full rounded-2xl px-4 text-sm text-foreground outline-none">
                </div>

                <div>
                    <label class="mb-2 block text-sm text-muted-foreground">Pesan</label>
                    <textarea x-model="swapForm.message" rows="4" class="surface-soft w-full resize-none rounded-2xl px-4 py-3 text-sm text-foreground outline-none placeholder:text-muted-foreground" placeholder="Tulis pesan singkat..."></textarea>
                </div>
            </div>

            <button
                type="button"
                @click="sendSwapRequest"
                :disabled="sendingRequest || !selectedPartner.skill_id"
                class="mt-6 flex w-full items-center justify-center gap-2 rounded-full bg-white px-5 py-3 text-sm font-medium text-black transition hover:scale-[1.01] disabled:cursor-not-allowed disabled:opacity-60"
            >
                <span x-text="sendingRequest ? 'Mengirim...' : 'Kirim request'"></span>
                <i data-lucide="send" class="h-4 w-4"></i>
            </button>
        </div>
    </div>

    <!-- Drawer permintaan masuk -->
    <div x-cloak x-show="requestsOpen" x-transition.opacity class="fixed inset-0 z-50 bg-black/70" @keydown.escape.window="requestsOpen = false">
        <aside x-show="requestsOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="surface absolute right-0 top-0 h-full w-full max-w-md overflow-y-auto p-6 md:p-8">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-muted-foreground">Inbox</p>
                    <h3 class="mt-2 font-display text-4xl">Swap requests</h3>
                </div>
                <button type="button" @click="requestsOpen = false" class="surface-soft grid h-10 w-10 place-items-center rounded-full">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>
            </div>

            <div class="mt-8 space-y-4">
                <template x-for="request in incomingRequests" :key="request.id">
                    <div class="surface-soft rounded-[24px] p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="font-medium" x-text="request.name"></p>
                                <p class="mt-1 text-sm text-muted-foreground" x-text="'@' + request.username"></p>
                            </div>
                            <span class="rounded-full border border-white/10 px-3 py-1 text-xs text-muted-foreground" x-text="request.status"></span>
                        </div>

                        <p class="mt-4 text-sm leading-6 text-muted-foreground">
                            Menawarkan <span class="text-foreground" x-text="request.offer"></span>
                            untuk belajar <span class="text-foreground" x-text="request.learn"></span>.
                        </p>

                        <div x-show="request.status === 'Menunggu'" class="mt-5 grid grid-cols-2 gap-3">
                            <button type="button" @click="rejectRequest(request)" class="surface-soft rounded-full px-4 py-2.5 text-sm text-muted-foreground transition hover:text-foreground">Tolak</button>
                            <button type="button" @click="acceptRequest(request)" class="rounded-full bg-white px-4 py-2.5 text-sm font-medium text-black">Terima</button>
                        </div>
                    </div>
                </template>
            </div>
        </aside>
    </div>

    <!-- Modal konfirmasi logout -->
    <div
        x-cloak
        x-show="logoutOpen"
        x-transition.opacity
        class="fixed inset-0 z-50 grid place-items-center bg-black/70 px-4"
        @keydown.escape.window="logoutOpen = false"
    >
        <div
            @click.outside="logoutOpen = false"
            class="surface w-full max-w-md rounded-[30px] p-6 md:p-7"
        >
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="surface-soft grid h-12 w-12 shrink-0 place-items-center rounded-full">
                        <i data-lucide="log-out" class="h-5 w-5 text-muted-foreground"></i>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-muted-foreground">Keluar</p>
                        <h3 class="mt-2 font-display text-3xl md:text-4xl">Apakah kamu ingin keluar?</h3>
                        <p class="mt-3 text-sm leading-6 text-muted-foreground">
                            Sesi kamu akan diakhiri dan kamu akan diarahkan ke beranda.
                        </p>
                    </div>
                </div>
                <button type="button" @click="logoutOpen = false" class="surface-soft grid h-10 w-10 shrink-0 place-items-center rounded-full">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>
            </div>

            <div class="mt-7 grid grid-cols-2 gap-3">
                <button
                    type="button"
                    @click="logoutOpen = false"
                    class="surface-soft rounded-full px-5 py-3 text-sm text-muted-foreground transition hover:text-foreground"
                >
                    Batal
                </button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-full bg-white px-5 py-3 text-sm font-medium text-black transition hover:scale-[1.01]"
                    >
                        Ya, keluar
                        <i data-lucide="arrow-right" class="h-4 w-4"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div x-cloak x-show="toast.show" x-transition class="fixed bottom-5 right-5 z-[60] max-w-sm rounded-2xl border border-white/10 bg-black/80 px-5 py-4 text-sm text-white shadow-2xl backdrop-blur-xl">
        <div class="flex items-start gap-3">
            <i data-lucide="check-circle-2" class="mt-0.5 h-4 w-4 shrink-0"></i>
            <span x-text="toast.message"></span>
        </div>
    </div>
</div>

<script>
    function skillShareApp() {
        return {
            query: '',
            category: 'Semua',
            sortDescending: true,
            swapOpen: false,
            requestsOpen: false,
            logoutOpen: false,
            sendingRequest: false,
            selectedPartner: { id: null, name: '', skill: '', skill_id: null },
            xpMeta: @js($xpMeta),
            swapForm: {
                offerSkill: @js(($myTeachSkills ?? collect())->first() ?? ''),
                message: 'Halo! Aku tertarik belajar bareng. Kita bisa saling tukar skill dan atur jadwal yang nyaman.'
            },
            incomingRequests: [
                { id: 1, name: 'Nadia Yusuf', username: 'nadia', offer: 'Figma', learn: 'Laravel', status: 'Menunggu' },
                { id: 2, name: 'Raka Aditya', username: 'raka', offer: 'Public Speaking', learn: 'UI/UX', status: 'Menunggu' },
            ],
            toast: { show: false, message: '' },

            partnerVisible(dataset) {
                const haystack = `${dataset.name || ''} ${dataset.skill || ''} ${dataset.category || ''} ${dataset.universityName || ''}`.toLowerCase();
                const matchesSearch = haystack.includes(this.query.toLowerCase().trim());
                const categoryList = (dataset.category || '')
                    .split(',')
                    .map((item) => item.trim())
                    .filter(Boolean);
                const matchesCategory = this.category === 'Semua' || categoryList.includes(this.category);

                return matchesSearch && matchesCategory;
            },

            toggleSort() {
                this.sortDescending = !this.sortDescending;
                this.applySort();
                this.$nextTick(() => lucide.createIcons());
            },

            applySort() {
                document.querySelectorAll('[data-partner-grid]').forEach((grid) => {
                    const cards = Array.from(grid.children);
                    cards.sort((a, b) => {
                        const matchA = parseInt(a.dataset.match || '0', 10);
                        const matchB = parseInt(b.dataset.match || '0', 10);
                        return this.sortDescending ? matchB - matchA : matchA - matchB;
                    });
                    cards.forEach((card) => grid.appendChild(card));
                });
            },

            openSwap(partner) {
                this.selectedPartner = {
                    id: partner.id ?? null,
                    name: partner.name ?? '',
                    skill: partner.skill ?? '',
                    skill_id: partner.skill_id ?? null,
                };
                this.swapOpen = true;
                this.$nextTick(() => lucide.createIcons());
            },

            async sendSwapRequest() {
                if (this.sendingRequest) return;

                if (!this.selectedPartner.id || !this.selectedPartner.skill_id) {
                    this.showToast('Partner atau skill belum tersedia.');
                    return;
                }

                this.sendingRequest = true;

                try {
                    const response = await fetch(@js(route('swap.send')), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({
                            receiver_id: this.selectedPartner.id,
                            skill_id: this.selectedPartner.skill_id,
                        }),
                    });

                    const data = await response.json().catch(() => ({}));

                    if (!response.ok || data.success === false) {
                        const message = data.message
                            || data.errors?.skill_id?.[0]
                            || data.errors?.receiver_id?.[0]
                            || 'Gagal mengirim request.';
                        this.showToast(message);
                        return;
                    }

                    if (data.user) {
                        this.xpMeta = data.user;
                    }

                    this.swapOpen = false;
                    const xpNote = data.xp_gained ? ` +${data.xp_gained} XP` : '';
                    this.showToast(data.message || `Request ke ${this.selectedPartner.name} berhasil dikirim.${xpNote}`);
                } catch (error) {
                    this.showToast('Terjadi kesalahan jaringan. Coba lagi.');
                } finally {
                    this.sendingRequest = false;
                    this.$nextTick(() => lucide.createIcons());
                }
            },

            acceptRequest(request) {
                request.status = 'Diterima';
                this.showToast(`Request dari ${request.name} diterima.`);
            },

            rejectRequest(request) {
                request.status = 'Ditolak';
                this.showToast(`Request dari ${request.name} ditolak.`);
            },

            showToast(message) {
                this.toast = { show: true, message };
                this.$nextTick(() => lucide.createIcons());
                setTimeout(() => this.toast.show = false, 2800);
            }
        }
    }

    document.addEventListener('DOMContentLoaded', () => lucide.createIcons());

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

    makeStars('stars-dashboard', 280);
</script>
</body>
</html>