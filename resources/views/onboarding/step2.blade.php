<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Skill · SkillShare</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>✦</text></svg>">
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            background: hsl(201, 100%, 8%);
            color: white;
            font-family: 'Inter', sans-serif;
            overflow: hidden;
        }

        canvas#stars-canvas {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
        }

        .top-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px 32px;
        }

        .logo {
            font-family: 'Instrument Serif', serif;
            font-size: 1.5rem;
            color: white;
            text-decoration: none;
        }

        .page-wrap {
            position: relative;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            padding: 80px 16px 24px;
        }

        .card {
            width: 100%;
            max-width: 540px;
            border-radius: 24px;
            position: relative;
        }

        .card::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 1px;
            background: linear-gradient(
                180deg,
                rgba(255, 255, 255, 0.25) 0%,
                rgba(255, 255, 255, 0.05) 40%,
                rgba(255, 255, 255, 0.00) 60%,
                rgba(255, 255, 255, 0.10) 100%
            );
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
            z-index: 1;
        }

        .card-inner {
            height: calc(100vh - 140px);
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: none;
            padding: 2.5rem;
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.02);
        }

        .card-inner::-webkit-scrollbar {
            display: none;
        }

        .btn-back {
            padding: 8px 20px;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            color: white;
            text-decoration: none;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.2);
            transition: background 0.2s;
        }

        .btn-back:hover {
            background: rgba(255,255,255,0.1);
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .nav-progress {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 5px;
        }

        .nav-progress .progress-bar {
            width: 120px;
        }
        .progress-label {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.4);
            margin-bottom: 8px;
        }

        .progress-bar {
            width: 100%;
            height: 3px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 9999px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: white;
            border-radius: 9999px;
            transition: width 0.4s;
        }

        .card-title {
            font-family: 'Instrument Serif', serif;
            font-size: 2rem;
            font-weight: 400;
            margin-bottom: 0.4rem;
            text-align: center;
        }

        .card-sub {
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.45);
            text-align: center;
            margin-bottom: 2rem;
        }

        .divider {
            border: none;
            border-top: 1px solid rgba(255, 255, 255, 0.07);
            margin: 1.75rem 0;
        }

    
        .skill-section-head {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .skill-section-head strong {
            color: white;
            font-weight: 600;
        }

       
        .search-wrap {
            position: relative;
            margin-bottom: 1rem;
        }

        .search-wrap input {
            width: 100%;
            padding: 10px 14px 10px 38px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: white;
            font-size: 0.875rem;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: border 0.2s;
        }

        .search-wrap input:focus {
            border-color: rgba(255, 255, 255, 0.3);
        }

        .search-wrap input::placeholder {
            color: rgba(255, 255, 255, 0.25);
        }

        .search-wrap .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.3);
            font-size: 0.9rem;
            pointer-events: none;
        }

    
        .skill-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            max-height: 200px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.1) transparent;
            padding-right: 4px;
            margin-bottom: 1rem;
        }

        .skill-grid::-webkit-scrollbar {
            width: 4px;
        }

        .skill-grid::-webkit-scrollbar-track {
            background: transparent;
        }

        .skill-grid::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 9999px;
        }

        .skill-chip {
            padding: 10px 8px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.04);
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.8rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.15s;
            user-select: none;
        }

        .skill-chip:hover {
            background: rgba(255, 255, 255, 0.09);
            border-color: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .skill-chip.selected {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.4);
            color: white;
        }

        .skill-chip.hidden {
            display: none;
        }

        .selected-wrap {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            min-height: 28px;
            margin-bottom: 0.5rem;
        }

        .selected-tag {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 9999px;
            font-size: 0.75rem;
            color: white;
        }

        .selected-tag button {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            padding: 0;
            font-size: 0.8rem;
            line-height: 1;
            transition: color 0.15s;
        }

        .selected-tag button:hover {
            color: white;
        }

        /* ── Errors ── */
        .field-error {
            font-size: 0.75rem;
            color: rgba(239, 68, 68, 0.9);
            margin-top: 5px;
            margin-bottom: 8px;
        }


        .btn-row {
            margin-top: 1.5rem;
        }

        .btn-secondary {
            width: 100%;
            padding: 12px;
            background: rgba(255, 255, 255, 0.06);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .btn-primary {
            width: 100%;
            padding: 12px;
            background: white;
            color: black;
            border: none;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .btn-primary:hover {
            opacity: 0.88;
        }
    </style>
</head>
<body>

<canvas id="stars-canvas"></canvas>

<nav class="top-nav">
    <a href="{{ route('landing') }}" class="logo">SkillShare</a>

    <div class="nav-right">
        <div class="nav-progress">
            <p class="progress-label">Langkah 2 dari 2</p>
            <div class="progress-bar">
                <div class="progress-fill" style="width: 100%"></div>
            </div>
        </div>
        <a href="{{ route('onboarding.step1') }}" class="btn-back">Kembali</a>
    </div>
</nav>

<div class="page-wrap">
    <div class="card">
        <div class="card-inner">

            <h1 class="card-title">Skill Kamu</h1>
            <p class="card-sub">Pilih apa yang bisa kamu ajarkan & yang ingin kamu pelajari.</p>

            <form method="POST" action="{{ route('onboarding.step2.save') }}" id="skill-form" novalidate>
                @csrf

                @error('teach')
                    <p class="field-error">{{ $message }}</p>
                @enderror
                <div class="skill-section-head">
                    <strong>Bisa saya ajarkan</strong>
                    <span>Pilih minimal 1</span>
                </div>

                <div class="search-wrap">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" placeholder="Cari skill..." oninput="filterSkills('teach', this.value)">
                </div>

                <div class="skill-grid" id="teach-grid">
                    @foreach ($categories as $cat)
                        @foreach ($cat->skills as $skill)
                            <div class="skill-chip"
                                 data-id="{{ $skill->id }}"
                                 data-name="{{ $skill->name }}"
                                 data-section="teach"
                                 onclick="toggleSkill('teach', {{ $skill->id }}, '{{ $skill->name }}', this)">
                                {{ $skill->name }}
                            </div>
                        @endforeach
                    @endforeach
                </div>

                <div class="selected-wrap" id="teach-tags"></div>

            
                <div id="teach-inputs"></div>

                <hr class="divider">

                @error('learn')
                    <p class="field-error">{{ $message }}</p>
                @enderror
                <div class="skill-section-head">
                    <strong>Ingin saya pelajari</strong>
                    <span>Pilih minimal 1</span>
                </div>

                <div class="search-wrap">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" placeholder="Cari skill..." oninput="filterSkills('learn', this.value)">
                </div>

                <div class="skill-grid" id="learn-grid">
                    @foreach ($categories as $cat)
                        @foreach ($cat->skills as $skill)
                            <div class="skill-chip"
                                 data-id="{{ $skill->id }}"
                                 data-name="{{ $skill->name }}"
                                 data-section="learn"
                                 onclick="toggleSkill('learn', {{ $skill->id }}, '{{ $skill->name }}', this)">
                                {{ $skill->name }}
                            </div>
                        @endforeach
                    @endforeach
                </div>

                <div class="selected-wrap" id="learn-tags"></div>


                <div id="learn-inputs"></div>

                <div class="btn-row">
                    <button type="submit" class="btn-primary">Selesai</button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    (function () {
        const canvas = document.getElementById('stars-canvas');
        const ctx    = canvas.getContext('2d');

        function resize() {
            canvas.width  = window.innerWidth;
            canvas.height = window.innerHeight;
        }
        resize();
        window.addEventListener('resize', resize);

        const stars = Array.from({ length: 140 }, () => ({
            x:     Math.random(),
            y:     Math.random(),
            r:     Math.random() * 1.2 + 0.3,
            speed: Math.random() * 0.012 + 0.004,
            phase: Math.random() * Math.PI * 2,
            lo:    Math.random() * 0.12 + 0.04,
            hi:    Math.random() * 0.55 + 0.25,
        }));

        let t = 0;

        function draw() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            t += 0.016;
            stars.forEach(s => {
                const alpha = s.lo + (s.hi - s.lo) * (0.5 + 0.5 * Math.sin(t * s.speed * 60 + s.phase));
                ctx.beginPath();
                ctx.arc(s.x * canvas.width, s.y * canvas.height, s.r, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(255,255,255,${alpha})`;
                ctx.fill();
            });
            requestAnimationFrame(draw);
        }
        draw();
    })();

    const selected = { teach: {}, learn: {} };

    function toggleSkill(section, id, name, el) {
        if (selected[section][id]) {
            removeSkill(section, id);
        } else {
            const other = section === 'teach' ? 'learn' : 'teach';
            if (selected[other][id]) {
                alert(`"${name}" sudah dipilih di bagian lain. Pilih skill yang berbeda.`);
                return;
            }
            selected[section][id] = name;
            el.classList.add('selected');
        }
        renderTags(section);
        renderInputs(section);
    }

    function removeSkill(section, id) {
        delete selected[section][id];

        const chip = document.querySelector(`#${section}-grid .skill-chip[data-id="${id}"]`);
        if (chip) chip.classList.remove('selected');

        renderTags(section);
        renderInputs(section);
    }

    function renderTags(section) {
        const wrap = document.getElementById(`${section}-tags`);
        wrap.innerHTML = '';
        Object.entries(selected[section]).forEach(([id, name]) => {
            const tag = document.createElement('div');
            tag.className = 'selected-tag';
            tag.innerHTML = `
                <span>${name}</span>
                <button type="button" onclick="removeSkill('${section}', ${id})">×</button>
            `;
            wrap.appendChild(tag);
        });
    }

    function renderInputs(section) {
        const wrap = document.getElementById(`${section}-inputs`);
        wrap.innerHTML = '';
        Object.keys(selected[section]).forEach(id => {
            const inp = document.createElement('input');
            inp.type  = 'hidden';
            inp.name  = `${section}[]`;
            inp.value = id;
            wrap.appendChild(inp);
        });
    }

    function filterSkills(section, query) {
        const q     = query.toLowerCase().trim();
        const chips = document.querySelectorAll(`#${section}-grid .skill-chip`);
        chips.forEach(chip => {
            const name = chip.dataset.name.toLowerCase();
            chip.classList.toggle('hidden', q.length > 0 && !name.includes(q));
        });
    }
</script>
</body>
</html>