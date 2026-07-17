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

            --background: 201 100% 13%;
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

        .video-bg {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: .17;
            z-index: -2;
        }

        .scrollbar-hidden::-webkit-scrollbar { display: none; }
        .scrollbar-hidden { scrollbar-width: none; }
    </style>
</head>
<body>
@php
    $user = [
        'name' => 'Budi',
        'initial' => 'B',
        'xp' => 80,
        'university' => 'Universitas Airlangga',
        'city' => 'Surabaya',
    ];

    $calculateMatch = function (array $partner): int {
        // Bobot: kecocokan skill 55%, skill yang ingin dipelajari 20%,
        // kampus sama 15%, lokasi sama 10%.
        $score = ($partner['skill_score'] * 0.55)
               + ($partner['goal_score'] * 0.20)
               + ($partner['same_campus'] ? 15 : 0)
               + ($partner['same_city'] ? 10 : 0);

        return min(100, max(0, (int) round($score)));
    };

    $bestPartners = [
        [
            'name' => 'Diego Martin', 'username' => 'diego', 'university' => 'Stanford University',
            'city' => 'California', 'skill' => 'Svelte', 'category' => 'Frontend',
            'bio' => 'Frontend engineer yang suka membangun UI cepat, ringan, dan accessible.',
            'avatar' => 'DM', 'skill_score' => 98, 'goal_score' => 100,
            'same_campus' => false, 'same_city' => false,
        ],
        [
            'name' => 'Lina Park', 'username' => 'lina', 'university' => 'Seoul National University',
            'city' => 'Seoul', 'skill' => 'Piano', 'category' => 'Language',
            'bio' => 'Pianis klasik yang suka bertukar skill musik dengan desain digital.',
            'avatar' => 'LP', 'skill_score' => 95, 'goal_score' => 95,
            'same_campus' => false, 'same_city' => false,
        ],
        [
            'name' => 'Kenji Rao', 'username' => 'kenji', 'university' => 'IIT Bombay',
            'city' => 'Mumbai', 'skill' => 'Hardware', 'category' => 'Backend',
            'bio' => 'Hardware enthusiast, embedded systems learner, dan mentor komunitas kampus.',
            'avatar' => 'KR', 'skill_score' => 91, 'goal_score' => 90,
            'same_campus' => false, 'same_city' => false,
        ],
    ];

    $campusPartners = [
        [
            'name' => 'Sam Pratama', 'username' => 'sam', 'university' => 'Universitas Airlangga',
            'city' => 'Surabaya', 'skill' => 'Guitar', 'category' => 'Language',
            'bio' => 'Gitaris kampus yang terbuka untuk sesi belajar santai setelah kelas.',
            'avatar' => 'SP', 'skill_score' => 73, 'goal_score' => 71,
            'same_campus' => true, 'same_city' => true,
        ],
        [
            'name' => 'Aria Putri', 'username' => 'aria', 'university' => 'Universitas Airlangga',
            'city' => 'Surabaya', 'skill' => 'UI/UX', 'category' => 'Design',
            'bio' => 'UI/UX designer yang fokus pada riset pengguna dan design system.',
            'avatar' => 'AP', 'skill_score' => 68, 'goal_score' => 66,
            'same_campus' => true, 'same_city' => true,
        ],
        [
            'name' => 'Leo Wijaya', 'username' => 'leo', 'university' => 'Universitas Airlangga',
            'city' => 'Surabaya', 'skill' => 'Math', 'category' => 'Data',
            'bio' => 'Tutor matematika yang sabar dan suka menjelaskan konsep dari dasar.',
            'avatar' => 'LW', 'skill_score' => 60, 'goal_score' => 60,
            'same_campus' => true, 'same_city' => true,
        ],
    ];

    $skills = [
        ['icon' => 'atom', 'name' => 'React', 'count' => 12],
        ['icon' => 'palette', 'name' => 'Figma', 'count' => 8],
        ['icon' => 'code-2', 'name' => 'Python', 'count' => 15],
        ['icon' => 'pen-tool', 'name' => 'UI/UX', 'count' => 6],
        ['icon' => 'terminal-square', 'name' => 'Laravel', 'count' => 9],
        ['icon' => 'globe-2', 'name' => 'Vue.js', 'count' => 5],
        ['icon' => 'bar-chart-3', 'name' => 'Data', 'count' => 11],
        ['icon' => 'cloud', 'name' => 'AWS', 'count' => 4],
    ];
@endphp

<!-- Simpan video di public/videos/skillshare-bg.mp4 -->
<video class="video-bg" autoplay muted loop playsinline aria-hidden="true">
    <source src="{{ asset('videos/skillshare-bg.mp4') }}" type="video/mp4">
</video>

<div
    x-data="skillShareApp()"
    x-init="$nextTick(() => lucide.createIcons())"
    class="min-h-screen"
>
    <header class="relative z-10">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-5 py-5 md:px-8 md:py-6">
            <a href="#" class="font-display text-3xl tracking-tight text-foreground">
                SkillShare<sup class="text-xs">®</sup>
            </a>

            <div class="hidden items-center gap-8 md:flex">
                <a href="#discover" class="text-sm text-foreground">Discover</a>
                <button type="button" @click="requestsOpen = true" class="relative text-sm text-muted-foreground transition-colors hover:text-foreground">
                    Requests
                    <span class="absolute -right-4 -top-3 inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-white px-1 text-[10px] font-medium text-black">2</span>
                </button>
                <a href="#skills" class="text-sm text-muted-foreground transition-colors hover:text-foreground">Explore</a>
                <a href="#profile" class="text-sm text-muted-foreground transition-colors hover:text-foreground">Profil</a>
            </div>

            <div class="flex items-center gap-2.5">
                <div class="liquid-glass hidden rounded-full px-4 py-2 text-sm text-foreground sm:flex sm:items-center sm:gap-2">
                    <i data-lucide="sparkles" class="h-4 w-4"></i>
                    +{{ $user['xp'] }} XP
                </div>

                <button type="button" id="profile" class="liquid-glass grid h-10 w-10 place-items-center rounded-full text-sm font-medium text-foreground">
                    {{ $user['initial'] }}
                </button>

                <button type="button" class="liquid-glass grid h-10 w-10 place-items-center rounded-full text-foreground" aria-label="Keluar">
                    <i data-lucide="log-out" class="h-4 w-4"></i>
                </button>
            </div>
        </nav>
    </header>

    <main id="discover" class="relative z-0 mx-auto max-w-7xl px-5 pb-24 pt-8 md:px-8 md:pt-14">
        <section class="mb-10 md:mb-14">
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
                        placeholder="Cari nama, skill, atau username..."
                        class="w-full bg-transparent text-sm text-foreground outline-none placeholder:text-muted-foreground"
                    >
                </label>

                <select x-model="category" class="surface-soft h-12 rounded-2xl px-4 text-sm text-foreground outline-none">
                    <option value="Semua" class="bg-[#061f2b]">Semua kategori</option>
                    <option value="Frontend" class="bg-[#061f2b]">Frontend</option>
                    <option value="Backend" class="bg-[#061f2b]">Backend</option>
                    <option value="Design" class="bg-[#061f2b]">Design</option>
                    <option value="Data" class="bg-[#061f2b]">Data</option>
                    <option value="Language" class="bg-[#061f2b]">Language</option>
                </select>

                <button type="button" @click="universityOnly = !universityOnly" :class="universityOnly ? 'bg-white text-black' : 'surface-soft text-foreground'" class="h-12 rounded-2xl px-5 text-xs font-medium tracking-[0.18em] transition">
                    UNIV
                </button>
            </div>

            <div class="scrollbar-hidden mt-5 flex gap-2 overflow-x-auto pb-1">
                <template x-for="item in categories" :key="item">
                    <button
                        type="button"
                        @click="category = item"
                        :class="category === item ? 'bg-white text-black' : 'surface-soft text-muted-foreground hover:text-foreground'"
                        class="whitespace-nowrap rounded-full px-4 py-2 text-sm transition"
                        x-text="item"
                    ></button>
                </template>
            </div>
        </section>

        <div class="dotted-divider"></div>

        <section class="py-12 md:py-16">
            <div class="mb-7 flex items-end justify-between gap-5">
                <div>
                    <p class="text-xs uppercase tracking-[0.22em] text-muted-foreground">Kurasi personal</p>
                    <h2 class="mt-2 font-display text-4xl tracking-tight md:text-5xl">Match terbaikmu</h2>
                    <p class="mt-2 text-sm text-muted-foreground">Cocok berdasarkan skill, kampus, dan lokasi.</p>
                </div>
                <button type="button" @click="sortDescending = !sortDescending" class="surface-soft hidden rounded-full px-4 py-2 text-sm text-muted-foreground transition hover:text-foreground sm:inline-flex sm:items-center sm:gap-2">
                    <i data-lucide="arrow-up-down" class="h-4 w-4"></i>
                    Urutkan
                </button>
            </div>

            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($bestPartners as $partner)
                    @php $match = $calculateMatch($partner); @endphp
                    <article
                        data-name="{{ strtolower($partner['name']) }}"
                        data-username="{{ strtolower($partner['username']) }}"
                        data-skill="{{ strtolower($partner['skill']) }}"
                        data-category="{{ $partner['category'] }}"
                        data-university="{{ $partner['same_campus'] ? 'same' : 'different' }}"
                        x-show="partnerVisible($el.dataset)"
                        x-transition.opacity.duration.200ms
                        class="surface group rounded-[28px] p-5 transition duration-300 hover:-translate-y-1 hover:border-white/20"
                    >
                        <div class="flex items-center justify-between gap-4">
                            <span class="rounded-full border border-white/10 px-3 py-1 text-xs text-muted-foreground">{{ $match }}% match</span>
                            <button type="button" class="text-muted-foreground transition hover:text-foreground" aria-label="Simpan partner">
                                <i data-lucide="bookmark" class="h-4 w-4"></i>
                            </button>
                        </div>

                        <div class="mt-6 flex items-center gap-4">
                            <div class="grid h-14 w-14 shrink-0 place-items-center rounded-full border border-white/10 bg-white/5 font-display text-xl">
                                {{ $partner['avatar'] }}
                            </div>
                            <div class="min-w-0">
                                <h3 class="truncate text-lg font-medium">{{ $partner['name'] }}</h3>
                                <p class="text-sm text-muted-foreground">@{{ $partner['username'] }}</p>
                            </div>
                        </div>

                        <div class="mt-5 flex items-start gap-2 text-sm text-muted-foreground">
                            <i data-lucide="graduation-cap" class="mt-0.5 h-4 w-4 shrink-0"></i>
                            <span>{{ $partner['university'] }}</span>
                        </div>

                        <div class="mt-5">
                            <p class="text-xs uppercase tracking-[0.18em] text-muted-foreground">Dia ajarkan</p>
                            <span class="mt-2 inline-flex rounded-full bg-white px-3 py-1 text-xs font-medium text-black">{{ $partner['skill'] }}</span>
                        </div>

                        <p class="mt-5 min-h-16 text-sm leading-6 text-muted-foreground">{{ $partner['bio'] }}</p>

                        <button
                            type="button"
                            @click="openSwap({ name: @js($partner['name']), username: @js($partner['username']), skill: @js($partner['skill']) })"
                            class="liquid-glass mt-6 flex w-full items-center justify-between rounded-full px-5 py-3 text-sm text-foreground transition duration-300 hover:scale-[1.02]"
                        >
                            <span>+ Kirim request</span>
                            <i data-lucide="arrow-right" class="h-4 w-4"></i>
                        </button>
                    </article>
                @endforeach
            </div>
        </section>

        <div class="dotted-divider"></div>

        <section id="skills" class="py-12 md:py-16">
            <div class="mb-7">
                <p class="text-xs uppercase tracking-[0.22em] text-muted-foreground">Explore</p>
                <h2 class="mt-2 font-display text-4xl tracking-tight md:text-5xl">Mau belajar skill lain?</h2>
                <p class="mt-2 text-sm text-muted-foreground">Coba explore berdasarkan skill yang kamu mau pelajari.</p>
            </div>

            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                @foreach ($skills as $skill)
                    <button type="button" @click="query = @js(strtolower($skill['name'])); category = 'Semua'; window.scrollTo({ top: 0, behavior: 'smooth' })" class="surface-soft group rounded-[24px] p-5 text-left transition duration-300 hover:-translate-y-1 hover:border-white/20">
                        <i data-lucide="{{ $skill['icon'] }}" class="h-6 w-6 text-muted-foreground transition group-hover:text-foreground"></i>
                        <h3 class="mt-7 text-base font-medium">{{ $skill['name'] }}</h3>
                        <p class="mt-1 text-sm text-muted-foreground">{{ $skill['count'] }} orang</p>
                    </button>
                @endforeach
            </div>
        </section>

        <div class="dotted-divider"></div>

        <section class="py-12 md:py-16">
            <div class="mb-7">
                <p class="text-xs uppercase tracking-[0.22em] text-muted-foreground">Di dekatmu</p>
                <h2 class="mt-2 font-display text-4xl tracking-tight md:text-5xl">Partner dari kampus yang sama</h2>
                <p class="mt-2 text-sm text-muted-foreground">Sesama mahasiswa di kotamu.</p>
            </div>

            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($campusPartners as $partner)
                    @php $match = $calculateMatch($partner); @endphp
                    <article
                        data-name="{{ strtolower($partner['name']) }}"
                        data-username="{{ strtolower($partner['username']) }}"
                        data-skill="{{ strtolower($partner['skill']) }}"
                        data-category="{{ $partner['category'] }}"
                        data-university="same"
                        x-show="partnerVisible($el.dataset)"
                        x-transition.opacity.duration.200ms
                        class="surface group rounded-[28px] p-5 transition duration-300 hover:-translate-y-1 hover:border-white/20"
                    >
                        <div class="flex items-center justify-between gap-4">
                            <span class="rounded-full border border-white/10 px-3 py-1 text-xs text-muted-foreground">{{ $match }}% match</span>
                            <span class="text-xs text-muted-foreground">{{ $partner['city'] }}</span>
                        </div>

                        <div class="mt-6 flex items-center gap-4">
                            <div class="grid h-14 w-14 shrink-0 place-items-center rounded-full border border-white/10 bg-white/5 font-display text-xl">
                                {{ $partner['avatar'] }}
                            </div>
                            <div class="min-w-0">
                                <h3 class="truncate text-lg font-medium">{{ $partner['name'] }}</h3>
                                <p class="text-sm text-muted-foreground">@{{ $partner['username'] }}</p>
                            </div>
                        </div>

                        <div class="mt-5 flex items-start gap-2 text-sm text-muted-foreground">
                            <i data-lucide="graduation-cap" class="mt-0.5 h-4 w-4 shrink-0"></i>
                            <span>{{ $partner['university'] }}</span>
                        </div>

                        <div class="mt-5">
                            <p class="text-xs uppercase tracking-[0.18em] text-muted-foreground">Dia ajarkan</p>
                            <span class="mt-2 inline-flex rounded-full bg-white px-3 py-1 text-xs font-medium text-black">{{ $partner['skill'] }}</span>
                        </div>

                        <p class="mt-5 min-h-16 text-sm leading-6 text-muted-foreground">{{ $partner['bio'] }}</p>

                        <button
                            type="button"
                            @click="openSwap({ name: @js($partner['name']), username: @js($partner['username']), skill: @js($partner['skill']) })"
                            class="liquid-glass mt-6 flex w-full items-center justify-between rounded-full px-5 py-3 text-sm text-foreground transition duration-300 hover:scale-[1.02]"
                        >
                            <span>+ Kirim request</span>
                            <i data-lucide="arrow-right" class="h-4 w-4"></i>
                        </button>
                    </article>
                @endforeach
            </div>
        </section>
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
                        <option class="bg-[#061f2b]">Laravel</option>
                        <option class="bg-[#061f2b]">UI/UX</option>
                        <option class="bg-[#061f2b]">Public Speaking</option>
                        <option class="bg-[#061f2b]">Bahasa Indonesia</option>
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

            <button type="button" @click="sendSwapRequest" class="mt-6 flex w-full items-center justify-center gap-2 rounded-full bg-white px-5 py-3 text-sm font-medium text-black transition hover:scale-[1.01]">
                Kirim request
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
            universityOnly: false,
            sortDescending: true,
            swapOpen: false,
            requestsOpen: false,
            selectedPartner: { name: '', username: '', skill: '' },
            swapForm: {
                offerSkill: 'Laravel',
                message: 'Halo! Aku tertarik belajar bareng. Kita bisa saling tukar skill dan atur jadwal yang nyaman.'
            },
            categories: ['Semua', 'Frontend', 'Backend', 'Design', 'Data', 'Language'],
            incomingRequests: [
                { id: 1, name: 'Nadia Yusuf', username: 'nadia', offer: 'Figma', learn: 'Laravel', status: 'Menunggu' },
                { id: 2, name: 'Raka Aditya', username: 'raka', offer: 'Public Speaking', learn: 'UI/UX', status: 'Menunggu' },
            ],
            toast: { show: false, message: '' },

            partnerVisible(dataset) {
                const haystack = `${dataset.name} ${dataset.username} ${dataset.skill}`.toLowerCase();
                const matchesSearch = haystack.includes(this.query.toLowerCase().trim());
                const matchesCategory = this.category === 'Semua' || dataset.category === this.category;
                const matchesUniversity = !this.universityOnly || dataset.university === 'same';

                return matchesSearch && matchesCategory && matchesUniversity;
            },

            openSwap(partner) {
                this.selectedPartner = { ...partner };
                this.swapOpen = true;
                this.$nextTick(() => lucide.createIcons());
            },

            sendSwapRequest() {
                this.swapOpen = false;
                this.showToast(`Request ke ${this.selectedPartner.name} berhasil dikirim.`);
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
</script>
</body>
</html>