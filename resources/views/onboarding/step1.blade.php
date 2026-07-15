<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kamu · SkillShare</title>
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
        }

        canvas#stars-canvas {
            position: fixed;
            inset: 0;
            z-index: 2;
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
            padding: 20px 32px;
        }


        .logo {
            font-family: 'Instrument Serif', serif;
            font-size: 1.4rem;
            color: white;
            text-decoration: none;
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

        .nav-progress {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 5px;
        }

        .nav-progress .progress-bar {
            width: 120px;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .progress-label {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.4);
            margin-bottom: 6px;
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
            width: 50%;
            background: white;
            border-radius: 9999px;
            transition: width 0.4s;
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
            max-width: 500px;
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

        .section-label {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.25);
            margin: 1.75rem 0 1rem;
        }

        .section-label span {
            font-size: 0.65rem;
            text-transform: none;
            letter-spacing: 0;
            color: rgba(255, 255, 255, 0.18);
        }

        .divider {
            border: none;
            border-top: 1px solid rgba(255, 255, 255, 0.07);
            margin: 1.5rem 0;
        }

        .photo-wrap {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            margin-bottom: 1.25rem;
        }

        .photo-preview {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.15);
            background: rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
        }

        .photo-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }

        .photo-preview img.visible {
            display: block;
        }

        .photo-icon {
            font-size: 1.75rem;
            color: rgba(255, 255, 255, 0.25);
        }

        .photo-icon.hidden {
            display: none;
        }

        .photo-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 500;
            color: white;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.15);
            cursor: pointer;
            transition: background 0.2s;
        }

        .photo-btn:hover {
            background: rgba(255, 255, 255, 0.12);
        }

        .photo-input {
            display: none;
        }

        .photo-hint {
            font-size: 0.72rem;
            color: rgba(255, 255, 255, 0.25);
            margin-top: 4px;
        }

        .field {
            margin-bottom: 1rem;
        }

        .field label {
            display: block;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.5);
            margin-bottom: 6px;
        }

        .field label span {
            color: rgba(255, 255, 255, 0.25);
        }

        .field input,
        .field textarea,
        .field select {
            width: 100%;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: white;
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: border 0.2s;
            -webkit-appearance: none;
            appearance: none;
        }

        .field textarea {
            resize: none;
            height: 90px;
        }

        .field select option {
            background: hsl(201, 100%, 10%);
            color: white;
        }

        .field input:focus,
        .field textarea:focus,
        .field select:focus {
            border-color: rgba(255, 255, 255, 0.3);
        }

        .field input::placeholder,
        .field textarea::placeholder {
            color: rgba(255, 255, 255, 0.25);
        }

        .field input.is-error,
        .field textarea.is-error,
        .field select.is-error {
            border-color: rgba(239, 68, 68, 0.6);
        }

        .field input:-webkit-autofill,
        .field input:-webkit-autofill:hover,
        .field input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0px 1000px hsl(201, 100%, 8%) inset;
            -webkit-text-fill-color: white;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: background-color 5000s ease-in-out 0s;
        }

        .field-error {
            font-size: 0.75rem;
            color: rgba(239, 68, 68, 0.9);
            margin-top: 5px;
        }

        .field-readonly {
            position: relative;
        }

        .field-readonly input {
            color: rgba(255, 255, 255, 0.35);
            cursor: not-allowed;
            padding-right: 48px;
        }

        .lock-icon {
            position: absolute;
            right: 13px;
            bottom: 14px;
            font-size: 0.85rem;
            line-height: 1;
            color: rgba(255, 255, 255, 0.2);
            pointer-events: none;
        }

        .row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .select-wrap {
            position: relative;
        }

        .select-wrap::after {
            content: '';
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 14px;
            height: 14px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='rgba(255,255,255,0.3)' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-size: contain;
            pointer-events: none;
        }

        .btn-primary {
            width: 100%;
            padding: 13px;
            background: white;
            color: black;
            border: none;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            margin-top: 1.25rem;
            transition: opacity 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
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
            <p class="progress-label">Langkah 1 dari 2</p>
            <div class="progress-bar">
                <div class="progress-fill"></div>
            </div>
        </div>
        <a href="{{ route('landing') }}" class="btn-back">Kembali</a>
    </div>
</nav>

<div class="page-wrap">
    <div class="card">
        <div class="card-inner">

            <h1 class="card-title">Tentang Kamu</h1>
            <p class="card-sub">Lengkapi profilmu sekali, dipakai selamanya.</p>

            <form method="POST" action="{{ route('onboarding.step1.save') }}" enctype="multipart/form-data" novalidate>
                @csrf

                <p class="section-label">Profil</p>

                <div class="photo-wrap">
                    <div class="photo-preview" id="photo-preview">
                        <img id="photo-img" src="" alt="foto">
                        <i id="photo-icon" class="bi bi-person-fill photo-icon"></i>
                    </div>
                    <div>
                        <input type="file" name="photo" id="photo-input" accept="image/jpeg,image/png" class="photo-input" onchange="previewPhoto(this)">
                        <button type="button" class="photo-btn" onclick="document.getElementById('photo-input').click()">
                            <i class="bi bi-upload"></i>
                            Unggah Foto
                        </button>
                        <p class="photo-hint">JPEG, JPG, PNG · maks 2MB</p>
                    </div>
                </div>

                <div class="field">
                    <label>Nama Tampil</label>
                    <input
                        type="text"
                        name="name"
                        placeholder="Nama lengkapmu"
                        value="{{ old('name', auth()->user()->name) }}"
                        class="{{ $errors->has('name') ? 'is-error' : '' }}"
                    >
                    @error('name')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label>Bio <span>(opsional)</span></label>
                    <textarea
                        name="bio"
                        placeholder="Ceritakan dirimu dalam beberapa kata..."
                        class="{{ $errors->has('bio') ? 'is-error' : '' }}"
                    >{{ old('bio', $user->bio) }}</textarea>
                    @error('bio')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <hr class="divider">
                <p class="section-label">Pendidikan</p>

                <div class="field">
                    <label>Kampus / Universitas</label>
                    <input
                        type="text"
                        name="university"
                        placeholder="cth. Universitas Indonesia"
                        value="{{ old('university', $user->university) }}"
                        class="{{ $errors->has('university') ? 'is-error' : '' }}"
                    >
                    @error('university')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label>Jurusan / Prodi</label>
                    <input
                        type="text"
                        name="major"
                        placeholder="cth. Teknik Informatika"
                        value="{{ old('major', $user->major) }}"
                        class="{{ $errors->has('major') ? 'is-error' : '' }}"
                    >
                    @error('major')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="row-2">
                    <div class="field">
                        <label>Semester</label>
                        <div class="select-wrap">
                            <select name="semester" class="{{ $errors->has('semester') ? 'is-error' : '' }}">
                                <option value="" disabled selected>Pilih...</option>
                                @for ($i = 1; $i <= 8; $i++)
                                    <option value="{{ $i }}" {{ old('semester') == $i || $user->semester == $i ? 'selected' : '' }}>
                                        Semester {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        @error('semester')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label>Kota <span>(opsional)</span></label>
                        <input
                            type="text"
                            name="city"
                            placeholder="cth. Depok"
                            value="{{ old('city', $user->city) }}"
                        >
                    </div>
                </div>

                <hr class="divider">
                <p class="section-label">
                    Kontak <span>· hanya terlihat saat request diterima</span>
                </p>

                <div class="field field-readonly">
                    <label>Email</label>
                    <input type="email" value="{{ auth()->user()->email }}" readonly>
                    <i class="bi bi-lock-fill lock-icon"></i>
                </div>

                <div class="field">
                    <label>WhatsApp <span>(opsional)</span></label>
                    <input
                        type="text"
                        name="whatsapp"
                        placeholder="08xxxxxxxxxx"
                        value="{{ old('whatsapp', $user->whatsapp) }}"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        class="{{ $errors->has('whatsapp') ? 'is-error' : '' }}"
                    >
                    @error('whatsapp')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label>Discord <span>(opsional)</span></label>
                    <input
                        type="text"
                        name="discord"
                        placeholder="username#1234"
                        value="{{ old('discord', $user->discord) }}"
                        class="{{ $errors->has('discord') ? 'is-error' : '' }}"
                    >
                    @error('discord')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label>Telegram <span>(opsional)</span></label>
                    <input
                        type="text"
                        name="telegram"
                        placeholder="@username"
                        value="{{ old('telegram', $user->telegram) }}"
                        class="{{ $errors->has('telegram') ? 'is-error' : '' }}"
                    >
                    @error('telegram')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-primary">
                    Lanjut
                </button>

            </form>
        </div>
    </div>
</div>

<script>
    (function () {
        const canvas = document.getElementById('stars-canvas');
        const ctx = canvas.getContext('2d');

        function resize() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }
        resize();
        window.addEventListener('resize', resize);

        const stars = Array.from({ length: 140 }, () => ({
            x: Math.random(),
            y: Math.random(),
            r: Math.random() * 1.2 + 0.3,
            speed: Math.random() * 0.012 + 0.004,
            phase: Math.random() * Math.PI * 2,
            lo: Math.random() * 0.12 + 0.04,
            hi: Math.random() * 0.55 + 0.25,
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

    function previewPhoto(input) {
        const file = input.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById('photo-img');
            const icon = document.getElementById('photo-icon');
            img.src = e.target.result;
            img.classList.add('visible');
            icon.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }
</script>
</body>
</html>