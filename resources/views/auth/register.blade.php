<!DOCTYPE html>
<html lang="id" class="js">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar · SkillShare</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>✦</text></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@400;500;600&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --accent: #7ab3ff;
            --accent-2: #a7ccff;
            --accent-deep: #4f8ff0;
            --ink: #eef2f8;
            --muted: rgba(238, 242, 248, 0.60);
            --faint: rgba(238, 242, 248, 0.40);
            --line: rgba(255, 255, 255, 0.10);
            --field: rgba(255, 255, 255, 0.045);
            --ease-spring: cubic-bezier(0.34, 1.56, 0.64, 1);
            --ease-out: cubic-bezier(0.22, 0.72, 0.16, 1);
        }

        html, body {
            width: 100%;
            min-height: 100vh;
            background: hsl(201, 100%, 8%);
            color: var(--ink);
            font-family: 'Manrope', 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        body {
            display: grid;
            place-items: center;
            padding: 40px 20px;
            overflow-x: hidden;
            position: relative;
        }

        .star {
            position: fixed;
            background: white;
            border-radius: 50%;
            pointer-events: none;
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

        .btn-back {
            padding: 8px 20px;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            color: white;
            text-decoration: none;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: background 0.2s;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .auth {
            position: relative;
            z-index: 1;
            width: min(92vw, 410px);
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 140px;
        }

        .fox-stage {
            position: relative;
            z-index: 3;
            width: 232px;
            margin-bottom: -46px;
            filter: drop-shadow(0 22px 26px rgba(0, 0, 0, 0.45));
            transform: translate3d(var(--fox-x, 0), var(--fox-y, 0), 0);
        }

        .fox {
            display: block;
            width: 100%;
            height: auto;
            overflow: visible;
        }

        .fox__shadow {
            fill: rgba(0, 0, 0, 0.28);
            filter: blur(4px);
        }

        .eye__ball {
            transform: translate(var(--px, 0px), var(--py, 0px));
        }

        .ear,
        .eye,
        .paw,
        .tongue {
            transform-box: fill-box;
            transform-origin: center;
        }

        .ear--l,
        .ear--r {
            transform-origin: 50% 94%;
        }

        .eye {
            transition: transform 0.12s ease;
        }

        .fox.is-blink .eye {
            transform: scaleY(0.08);
        }

        .mouth {
            fill: none;
            stroke: #2b2b33;
            stroke-width: 3;
            stroke-linecap: round;
        }

        .tongue {
            opacity: 0;
            transform-origin: center top;
            transform: scaleY(0.35);
            transition: opacity 0.25s ease, transform 0.32s var(--ease-spring);
        }

        .blush {
            opacity: 0.5;
            transition: opacity 0.35s ease;
        }

        .ear {
            transition: transform 0.4s var(--ease-spring);
        }

        .paw {
            transform: translateY(116px) scale(0.88);
            transition: transform 0.44s var(--ease-spring);
            filter: drop-shadow(0 6px 6px rgba(0, 0, 0, 0.22));
        }

        .fox.is-cover .paw {
            transform: translateY(0) scale(1);
        }

        .fox.is-cover .blush {
            opacity: 0.95;
        }

        .fox.is-cover .ear--l {
            transform: rotate(-9deg);
        }

        .fox.is-cover .ear--r {
            transform: rotate(9deg);
        }

        .fox.is-peek .paw {
            transform: translateY(50px) scale(1);
            transition: transform 0.34s var(--ease-out);
        }

        .fox.is-peek .blush {
            opacity: 0.95;
        }

        .fox.is-peek .ear--l {
            transform: rotate(-5deg);
        }

        .fox.is-peek .ear--r {
            transform: rotate(5deg);
        }

        .fox.is-happy .eye {
            transform: scaleY(0.18);
        }

        .fox.is-happy .blush {
            opacity: 0.85;
        }

        .fox.is-happy .mouth {
            transform: translateY(1px);
        }

        .fox.is-happy .tongue {
            opacity: 1;
            transform: scaleY(1);
        }

        .fox {
            animation: fox-bob 5.2s ease-in-out infinite;
        }

        @keyframes fox-bob {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-5px) rotate(-0.6deg);
            }
        }

        .card {
            position: relative;
            z-index: 2;
            width: 100%;
            padding: 58px 34px 30px;
            border-radius: 26px;
            background: linear-gradient(162deg, rgba(42, 48, 64, 0.62), rgba(17, 21, 31, 0.5));
            backdrop-filter: blur(26px) saturate(1.3);
            -webkit-backdrop-filter: blur(26px) saturate(1.3);
            border: 1px solid rgba(255, 255, 255, 0.14);
            box-shadow:
                0 44px 90px rgba(0, 0, 0, 0.5),
                0 2px 8px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.18);
            overflow: hidden;
        }

        .card__sheen {
            position: absolute;
            inset: 0;
            border-radius: inherit;
            pointer-events: none;
            background: radial-gradient(280px circle at var(--mx, 50%) var(--my, -10%), rgba(255, 255, 255, 0.14), transparent 45%);
        }

        .card__head {
            text-align: center;
            margin-bottom: 22px;
        }

        .brand {
            font-family: 'Instrument Serif', serif;
            font-size: 2.1rem;
            font-weight: 400;
            color: white;
        }

        .sub {
            margin-top: 5px;
            font-size: 0.9rem;
            font-weight: 400;
            color: var(--muted);
        }

        .form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .field {
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .input-wrap {
            position: relative;
        }

        .field input {
            width: 100%;
            padding: 21px 16px 8px;
            font-size: 1rem;
            font-weight: 500;
            color: var(--ink);
            background: var(--field);
            border: 1px solid var(--line);
            border-radius: 13px;
            outline: none;
            font-family: 'Manrope', 'Inter', sans-serif;
            transition: border-color 0.25s ease, background 0.25s ease, box-shadow 0.25s ease;
        }

        .field input:focus {
            border-color: var(--accent);
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 0 3px rgba(122, 179, 255, 0.22);
        }

        .field label {
            position: absolute;
            left: 16px;
            top: 16px;
            font-size: 1rem;
            color: var(--muted);
            pointer-events: none;
            transform-origin: left top;
            transition: transform 0.2s ease, color 0.2s ease;
            z-index: 1;
        }

        .field input:focus + label,
        .field input:not(:placeholder-shown) + label {
            transform: translateY(-11px) scale(0.72);
            color: var(--accent-2);
        }

        .field input.is-error {
            border-color: rgba(239, 68, 68, 0.6);
        }

        .field-error {
            font-size: 0.75rem;
            color: rgba(239, 68, 68, 0.9);
            margin-top: 5px;
        }

        .reveal {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            width: 36px;
            height: 36px;
            display: grid;
            place-items: center;
            background: transparent;
            border: 0;
            border-radius: 10px;
            color: var(--muted);
            cursor: pointer;
            transition: color 0.2s ease, background 0.2s ease;
        }

        .reveal:hover {
            color: var(--ink);
            background: rgba(255, 255, 255, 0.08);
        }

        .reveal svg {
            width: 20px;
            height: 20px;
        }

        .reveal .icon-off {
            display: none;
        }

        .reveal.is-on .icon-on {
            display: none;
        }

        .reveal.is-on .icon-off {
            display: block;
        }

        .pw-input {
            padding-right: 48px !important;
        }

        .strength-bar {
            margin-top: 6px;
            height: 3px;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.08);
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            border-radius: 9999px;
            transition: width 0.3s, background 0.3s;
        }

        .strength-label {
            font-size: 0.72rem;
            color: rgba(255, 255, 255, 0.35);
            margin-top: 4px;
        }

        .link {
            color: var(--accent-2);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .link:hover {
            color: var(--accent);
            text-decoration: underline;
        }

        .btn {
            position: relative;
            margin-top: 6px;
            min-height: 60px;
            padding: 0 18px 0 26px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0;
            font-size: 1.02rem;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(135deg, #3f7fe0 0%, #2456a6 54%, #1b3f7e 100%);
            border: 1px solid rgba(150, 190, 255, 0.34);
            border-radius: 14px;
            cursor: pointer;
            isolation: isolate;
            box-shadow:
                0 16px 30px -10px rgba(31, 79, 160, 0.6),
                inset 0 1px 0 rgba(255, 255, 255, 0.16);
            transition: transform 0.2s var(--ease-out), box-shadow 0.2s ease, filter 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            filter: brightness(1.05);
            box-shadow:
                0 22px 40px -12px rgba(31, 79, 160, 0.72),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }

        .btn:active {
            transform: translateY(0) scale(0.99);
        }

        .btn:disabled {
            cursor: default;
        }

        .doorbtn__label {
            position: relative;
            z-index: 1;
            letter-spacing: 0.01em;
            white-space: nowrap;
            transition: opacity 0.3s ease;
        }

        .btn.walking .doorbtn__label {
            opacity: 0.6;
        }

        .dooric {
            position: relative;
            width: 62px;
            height: 38px;
            flex: none;
            perspective: 300px;
            z-index: 1;
        }

        .dooric__frame {
            position: absolute;
            right: 0;
            top: 2px;
            width: 28px;
            height: 34px;
            border-radius: 5px;
            overflow: hidden;
            background: linear-gradient(180deg, #0b1a38, #060d20);
            box-shadow: inset 0 0 0 1.5px rgba(150, 190, 255, 0.3);
        }

        .dooric__glow {
            position: absolute;
            inset: 0;
            background: radial-gradient(62% 72% at 50% 46%, rgba(255, 214, 150, 0.95), rgba(255, 190, 110, 0) 72%);
            opacity: 0;
            transition: opacity 0.35s var(--ease-out);
        }

        .dooric__panel {
            position: absolute;
            right: 0;
            top: 2px;
            width: 28px;
            height: 34px;
            border-radius: 5px;
            background: linear-gradient(180deg, var(--accent-2), var(--accent) 55%, var(--accent-deep));
            box-shadow:
                inset 0 0 0 1.5px rgba(255, 255, 255, 0.24),
                inset -6px 0 10px rgba(0, 0, 0, 0.28);
            transform-origin: right center;
            transform: rotateY(0deg);
            transition: transform 0.42s var(--ease-out);
            backface-visibility: hidden;
        }

        .dooric__handle {
            position: absolute;
            left: 4px;
            top: 50%;
            width: 2.5px;
            height: 10px;
            border-radius: 2px;
            transform: translateY(-50%);
            background: linear-gradient(180deg, #fff, #cfe0ff);
        }

        .dooric__person {
            position: absolute;
            left: 2px;
            bottom: 2px;
            color: #fff;
            transition: transform 0.74s var(--ease-out), opacity 0.3s var(--ease-out) 0.36s;
        }

        .dooric__person svg {
            display: block;
            overflow: visible;
        }

        .leg,
        .arm,
        .shin {
            transform-box: fill-box;
            transform-origin: 50% 0;
        }

        .person__bob,
        .person {
            transform-box: fill-box;
            transform-origin: 50% 100%;
        }

        .leg--front { transform: rotate(11deg); }
        .leg--back  { transform: rotate(-11deg); }
        .arm--front { transform: rotate(-9deg); }
        .arm--back  { transform: rotate(9deg); }
        .leg--front .shin,
        .leg--back .shin { transform: rotate(7deg); }

        .person {
            transition: transform 0.25s var(--ease-out);
        }

        .btn.walking .person              { transform: rotate(4deg); }
        .btn.walking .leg--front          { animation: thighF 0.46s linear infinite; }
        .btn.walking .leg--back           { animation: thighB 0.46s linear infinite; }
        .btn.walking .leg--front .shin    { animation: shinF  0.46s linear infinite; }
        .btn.walking .leg--back  .shin    { animation: shinB  0.46s linear infinite; }
        .btn.walking .arm--front          { animation: armF   0.46s linear infinite; }
        .btn.walking .arm--back           { animation: armB   0.46s linear infinite; }
        .btn.walking .person__bob         { animation: bob    0.23s linear infinite; }

        @keyframes thighF { 0%,100%{transform:rotate(26deg);}  50%{transform:rotate(-24deg);} }
        @keyframes thighB { 0%,100%{transform:rotate(-24deg);} 50%{transform:rotate(26deg);} }
        @keyframes armF   { 0%,100%{transform:rotate(-24deg);} 50%{transform:rotate(24deg);} }
        @keyframes armB   { 0%,100%{transform:rotate(24deg);}  50%{transform:rotate(-24deg);} }
        @keyframes shinF  { 0%{transform:rotate(8deg);} 25%{transform:rotate(48deg);} 55%{transform:rotate(10deg);} 100%{transform:rotate(8deg);} }
        @keyframes shinB  { 0%{transform:rotate(34deg);} 30%{transform:rotate(6deg);} 78%{transform:rotate(44deg);} 100%{transform:rotate(34deg);} }
        @keyframes bob    { 0%,100%{transform:translateY(0);} 50%{transform:translateY(-1.4px);} }

        .btn.dooropen .dooric__panel  { transform: rotateY(72deg); }
        .btn.dooropen .dooric__glow   { opacity: 0.95; }
        .btn.out .dooric__person      { transform: translateX(36px) scale(0.72); opacity: 0; }

        .status {
            min-height: 0;
            font-size: 0.8rem;
            color: var(--muted);
            text-align: center;
        }

        .status:empty {
            display: none;
        }

        .foot {
            text-align: center;
            font-size: 0.85rem;
            color: var(--muted);
            margin-top: 16px;
        }

        html.js .fox-stage {
            opacity: 0;
            transform: translateY(24px) scale(0.9);
        }

        html.js .card > * {
            opacity: 0;
            transform: translateY(14px);
        }

        html.js .card {
            opacity: 0;
            transform: translateY(26px) scale(0.985);
        }

        html.js .auth.is-in .fox-stage {
            opacity: 1;
            transform: translate3d(var(--fox-x, 0), var(--fox-y, 0), 0);
            transition: opacity 0.6s ease, transform 0.7s var(--ease-spring);
        }

        html.js .auth.is-in .card {
            opacity: 1;
            transform: none;
            transition: opacity 0.6s ease 0.08s, transform 0.7s var(--ease-out) 0.08s;
        }

        html.js .auth.is-in .card > * {
            opacity: 1;
            transform: none;
            transition: opacity 0.5s ease, transform 0.5s var(--ease-out);
        }

        html.js .auth.is-in .card > :nth-child(2) { transition-delay: 0.16s; }
        html.js .auth.is-in .card > :nth-child(3) { transition-delay: 0.22s; }

        @media (max-width: 400px) {
            .card { padding: 54px 26px 26px; }
            .fox-stage { width: 190px; margin-bottom: -36px; }
            .brand { font-size: 1.9rem; }
        }

        @media (prefers-reduced-motion: reduce) {
            .fox { animation: none; }
            html.js .fox-stage,
            html.js .card,
            html.js .card > * {
                opacity: 1 !important;
                transform: none !important;
                transition: none !important;
            }
            .paw { transition: transform 0.2s ease; }
            .btn.walking .leg,
            .btn.walking .arm,
            .btn.walking .person__bob { animation: none !important; }
        }
    </style>
</head>
<body>

<nav class="top-nav">
    <a href="{{ route('landing') }}" class="logo">SkillShare</a>
    <a href="{{ route('landing') }}" class="btn-back">Kembali</a>
</nav>

<div id="stars"></div>

<main class="auth">
    <div class="fox-stage" aria-hidden="true">
        <svg class="fox" viewBox="0 0 320 300" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <radialGradient id="faceGrad" cx="50%" cy="40%" r="72%">
                    <stop offset="0%" stop-color="#ffffff"/>
                    <stop offset="100%" stop-color="#e6eefb"/>
                </radialGradient>
                <radialGradient id="furGrad" cx="50%" cy="24%" r="84%">
                    <stop offset="0%" stop-color="#5d6782"/>
                    <stop offset="100%" stop-color="#2e3444"/>
                </radialGradient>
                <radialGradient id="pawGrad" cx="50%" cy="26%" r="84%">
                    <stop offset="0%" stop-color="#57607a"/>
                    <stop offset="100%" stop-color="#2a303f"/>
                </radialGradient>
                <radialGradient id="irisGrad" cx="42%" cy="32%" r="74%">
                    <stop offset="0%" stop-color="#dcefff"/>
                    <stop offset="38%" stop-color="#8dc2f2"/>
                    <stop offset="74%" stop-color="#3f86cf"/>
                    <stop offset="100%" stop-color="#255f9c"/>
                </radialGradient>
                <radialGradient id="sheenGrad" cx="50%" cy="50%" r="50%">
                    <stop offset="0%" stop-color="#ffffff" stop-opacity="0.55"/>
                    <stop offset="100%" stop-color="#ffffff" stop-opacity="0"/>
                </radialGradient>
            </defs>

            <ellipse class="fox__shadow" cx="160" cy="270" rx="84" ry="15"/>
            <ellipse class="body" cx="160" cy="258" rx="62" ry="46" fill="url(#furGrad)"/>
            <path class="bib" d="M160 224 C182 236 190 262 178 290 C160 297 160 297 142 290 C130 262 138 236 160 224 Z" fill="url(#faceGrad)"/>

            <g class="ears">
                <g class="ear ear--l">
                    <path d="M96 96 Q78 60 80 26 Q81 12 93 22 Q120 46 130 78 Q114 98 96 96 Z" fill="url(#furGrad)"/>
                    <path d="M99 88 Q86 60 88 36 Q97 46 118 76 Q110 90 99 88 Z" fill="#c8d0e2"/>
                </g>
                <g class="ear ear--r">
                    <path d="M224 96 Q242 60 240 26 Q239 12 227 22 Q200 46 190 78 Q206 98 224 96 Z" fill="url(#furGrad)"/>
                    <path d="M221 88 Q234 60 232 36 Q223 46 202 76 Q210 90 221 88 Z" fill="#c8d0e2"/>
                </g>
            </g>

            <g class="head">
                <ellipse class="head__base" cx="160" cy="144" rx="100" ry="90" fill="url(#faceGrad)"/>
                <path class="crown" d="M62 130 Q58 62 160 56 Q262 62 258 130 Q236 118 214 120 Q186 132 160 96 Q134 132 106 120 Q84 118 62 130 Z" fill="url(#furGrad)"/>
                <ellipse class="head__sheen" cx="128" cy="98" rx="56" ry="28" fill="url(#sheenGrad)"/>
                <ellipse class="patch patch--l" cx="120" cy="112" rx="9" ry="6" fill="#c7d2e6" opacity="0.7" transform="rotate(-16 120 112)"/>
                <ellipse class="patch patch--r" cx="200" cy="112" rx="9" ry="6" fill="#c7d2e6" opacity="0.7" transform="rotate(16 200 112)"/>
                <ellipse class="blush blush--l" cx="99" cy="172" rx="16" ry="9" fill="#f2b79a"/>
                <ellipse class="blush blush--r" cx="221" cy="172" rx="16" ry="9" fill="#f2b79a"/>
                <ellipse class="muzzle__shadow" cx="160" cy="176" rx="43" ry="32" fill="#d8e2f1" opacity="0.7"/>
                <ellipse class="muzzle" cx="160" cy="170" rx="40" ry="29" fill="url(#faceGrad)"/>

                <g class="eye eye--l">
                    <ellipse class="eye__white" cx="123" cy="136" rx="16.5" ry="18" fill="#f4f8ff"/>
                    <g class="eye__ball">
                        <circle class="iris" cx="123" cy="137" r="12.5" fill="url(#irisGrad)"/>
                        <circle class="pupil" cx="123" cy="138" r="6.6" fill="#171b26"/>
                        <circle class="glint" cx="128" cy="132" r="4.4" fill="#fff"/>
                        <circle class="glint glint--sm" cx="118" cy="142" r="2" fill="#fff" opacity="0.85"/>
                    </g>
                    <path class="eye__lid" d="M108 132 Q123 119 138 132 Q123 127 108 132 Z" fill="#1d2740" opacity="0.14"/>
                </g>
                <g class="eye eye--r">
                    <ellipse class="eye__white" cx="197" cy="136" rx="16.5" ry="18" fill="#f4f8ff"/>
                    <g class="eye__ball">
                        <circle class="iris" cx="197" cy="137" r="12.5" fill="url(#irisGrad)"/>
                        <circle class="pupil" cx="197" cy="138" r="6.6" fill="#171b26"/>
                        <circle class="glint" cx="202" cy="132" r="4.4" fill="#fff"/>
                        <circle class="glint glint--sm" cx="192" cy="142" r="2" fill="#fff" opacity="0.85"/>
                    </g>
                    <path class="eye__lid" d="M182 132 Q197 119 212 132 Q197 127 182 132 Z" fill="#1d2740" opacity="0.14"/>
                </g>

                <path class="nose" d="M160 150 C175 150 181 161 173 168 C168 172 152 172 147 168 C139 161 145 150 160 150 Z" fill="#23262f"/>
                <path class="tongue" d="M149 184 Q160 205 171 184 Q160 191 149 184 Z" fill="#ee8fa0"/>
                <path class="mouth" d="M160 170 L160 182 M160 182 Q147 191 137 185 M160 182 Q173 191 183 185"/>

                <g class="whiskers" fill="#9aa6bd" opacity="0.5">
                    <circle cx="126" cy="176" r="1.3"/>
                    <circle cx="123" cy="183" r="1.3"/>
                    <circle cx="128" cy="189" r="1.3"/>
                    <circle cx="194" cy="176" r="1.3"/>
                    <circle cx="197" cy="183" r="1.3"/>
                    <circle cx="192" cy="189" r="1.3"/>
                </g>
            </g>

            <g class="paw paw--l">
                <ellipse class="paw__pad" cx="123" cy="134" rx="31" ry="29" fill="url(#pawGrad)"/>
                <ellipse class="paw__palm" cx="123" cy="143" rx="14" ry="11" fill="#aab3c8"/>
                <ellipse class="paw__toe" cx="106" cy="120" rx="6" ry="7.5" fill="#c3cbdd"/>
                <ellipse class="paw__toe" cx="123" cy="115" rx="6" ry="7.5" fill="#c3cbdd"/>
                <ellipse class="paw__toe" cx="140" cy="120" rx="6" ry="7.5" fill="#c3cbdd"/>
            </g>
            <g class="paw paw--r">
                <ellipse class="paw__pad" cx="197" cy="134" rx="31" ry="29" fill="url(#pawGrad)"/>
                <ellipse class="paw__palm" cx="197" cy="143" rx="14" ry="11" fill="#aab3c8"/>
                <ellipse class="paw__toe" cx="180" cy="120" rx="6" ry="7.5" fill="#c3cbdd"/>
                <ellipse class="paw__toe" cx="197" cy="115" rx="6" ry="7.5" fill="#c3cbdd"/>
                <ellipse class="paw__toe" cx="214" cy="120" rx="6" ry="7.5" fill="#c3cbdd"/>
            </g>
        </svg>
    </div>

    <section class="card">
        <span class="card__sheen" aria-hidden="true"></span>

        <header class="card__head">
            <h1 class="brand">SkillShare</h1>
            <p class="sub">Daftar gratis, belajar tanpa batas.</p>
        </header>

        <form class="form" method="POST" action="{{ route('register') }}" novalidate>
            @csrf

            <div class="field">
                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder=" "
                    value="{{ old('name') }}"
                    autocomplete="name"
                    required
                    class="{{ $errors->has('name') ? 'is-error' : '' }}"
                >
                <label for="name">Nama lengkap</label>
                @error('name')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder=" "
                    value="{{ old('email') }}"
                    autocomplete="email"
                    required
                    class="{{ $errors->has('email') ? 'is-error' : '' }}"
                >
                <label for="email">Email</label>
                @error('email')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <div class="input-wrap">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder=" "
                        autocomplete="new-password"
                        required
                        class="pw-input {{ $errors->has('password') ? 'is-error' : '' }}"
                    >
                    <label for="password">Kata sandi</label>
                    <button type="button" class="reveal" id="reveal-pw" aria-pressed="false" aria-label="Tampilkan kata sandi">
                        <svg class="icon-on" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg class="icon-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M3 3l18 18"/>
                            <path d="M10.6 6.2A9.7 9.7 0 0 1 12 5c6.4 0 10 7 10 7a17 17 0 0 1-3.2 3.9M6.2 6.3A17 17 0 0 0 2 12s3.6 7 10 7a9.6 9.6 0 0 0 4-.9"/>
                            <path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/>
                        </svg>
                    </button>
                </div>
                <div class="strength-bar">
                    <div class="strength-fill" id="strength-fill"></div>
                </div>
                <p class="strength-label" id="strength-label"></p>
                @error('password')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <div class="input-wrap">
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder=" "
                        autocomplete="new-password"
                        required
                        class="pw-input"
                    >
                    <label for="password_confirmation">Konfirmasi kata sandi</label>
                    <button type="button" class="reveal" id="reveal-pw2" aria-pressed="false" aria-label="Tampilkan kata sandi">
                        <svg class="icon-on" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg class="icon-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M3 3l18 18"/>
                            <path d="M10.6 6.2A9.7 9.7 0 0 1 12 5c6.4 0 10 7 10 7a17 17 0 0 1-3.2 3.9M6.2 6.3A17 17 0 0 0 2 12s3.6 7 10 7a9.6 9.6 0 0 0 4-.9"/>
                            <path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn doorbtn">
                <span class="doorbtn__label">Buat Akun</span>
                <span class="dooric" aria-hidden="true">
                    <span class="dooric__frame"><span class="dooric__glow"></span></span>
                    <span class="dooric__panel"><span class="dooric__handle"></span></span>
                    <span class="dooric__person">
                        <svg viewBox="0 0 26 44" width="17" height="29" fill="none" stroke="currentColor" stroke-width="2.7" stroke-linecap="round" stroke-linejoin="round">
                            <g class="person">
                                <g class="person__bob">
                                    <g class="leg leg--back"><line x1="13" y1="25" x2="13" y2="34"/><g class="shin"><line x1="13" y1="34" x2="13" y2="42.5"/></g></g>
                                    <g class="arm arm--back"><line x1="13" y1="14.5" x2="13" y2="24"/></g>
                                    <circle cx="13" cy="7" r="4" fill="currentColor" stroke="none"/>
                                    <line x1="13" y1="11" x2="13" y2="25.5"/>
                                    <g class="leg leg--front"><line x1="13" y1="25" x2="13" y2="34"/><g class="shin"><line x1="13" y1="34" x2="13" y2="42.5"/></g></g>
                                    <g class="arm arm--front"><line x1="13" y1="14.5" x2="13" y2="24"/></g>
                                </g>
                            </g>
                        </svg>
                    </span>
                </span>
            </button>
            <p class="status" role="status" aria-live="polite"></p>
        </form>

        <p class="foot">Sudah punya akun? <a href="{{ route('login') }}" class="link">Masuk</a></p>
    </section>
</main>

<script>
function makeStars() {
    const c = document.getElementById('stars');
    for (let i = 0; i < 200; i++) {
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
makeStars();

const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
const fine   = matchMedia('(hover: hover) and (pointer: fine)').matches;

const auth    = document.querySelector('.auth');
const stage   = document.querySelector('.fox-stage');
const fox     = document.querySelector('.fox');
const card    = document.querySelector('.card');
const nameEl  = document.getElementById('name');
const emailEl = document.getElementById('email');
const passEl  = document.getElementById('password');
const pass2El = document.getElementById('password_confirmation');
const reveal1 = document.getElementById('reveal-pw');
const reveal2 = document.getElementById('reveal-pw2');
const form    = document.querySelector('.form');
const btn     = form.querySelector('.btn');
const status  = form.querySelector('.status');

requestAnimationFrame(() => auth.classList.add('is-in'));

const MAX_X = 5, MAX_Y = 5;
let pointer = null;
let eyeMode = 'cursor';
let tx = 0, ty = 0, cx = 0, cy = 0;
let eyeRAF = null;

function foxCenter() {
    const r = stage.getBoundingClientRect();
    return { x: r.left + r.width / 2, y: r.top + r.height * 0.42 };
}

function computeTarget() {
    if (eyeMode === 'caret') { tx = 0; ty = 3.5; return; }
    if (eyeMode === 'up')    { tx = 0; ty = -4.5; return; }
    if (!pointer)            { tx = 0; ty = 0; return; }
    const c = foxCenter();
    const dx = pointer.x - c.x, dy = pointer.y - c.y;
    const dist = Math.hypot(dx, dy) || 1;
    tx = (dx / dist) * MAX_X * Math.min(dist / 260, 1);
    ty = (dy / dist) * MAX_Y * Math.min(dist / 260, 1);
}

function eyeLoop() {
    computeTarget();
    cx += (tx - cx) * 0.16;
    cy += (ty - cy) * 0.16;
    fox.style.setProperty('--px', `${cx.toFixed(2)}px`);
    fox.style.setProperty('--py', `${cy.toFixed(2)}px`);
    const settled = Math.abs(tx - cx) < 0.02 && Math.abs(ty - cy) < 0.02;
    eyeRAF = (settled && eyeMode === 'cursor' && !pointer) ? null : requestAnimationFrame(eyeLoop);
}
function kickEyes() { if (!eyeRAF) eyeRAF = requestAnimationFrame(eyeLoop); }

if (!reduce) {
    eyeRAF = requestAnimationFrame(eyeLoop);
    if (fine) {
        addEventListener('pointermove', (e) => { pointer = { x: e.clientX, y: e.clientY }; kickEyes(); }, { passive: true });
        addEventListener('pointerleave', () => { pointer = null; });
    }
}

if (!reduce) {
    const blink = () => {
        if (!fox.classList.contains('is-cover') && !fox.classList.contains('is-happy')) {
            fox.classList.add('is-blink');
            setTimeout(() => fox.classList.remove('is-blink'), 130);
        }
        setTimeout(blink, 2600 + Math.random() * 3600);
    };
    setTimeout(blink, 2600);
}

function applyFoxState() {
    fox.classList.remove('is-cover', 'is-peek');
    const active = document.activeElement;
    const pwShowing = passEl.type === 'text';
    const pw2Showing = pass2El.type === 'text';

    if (active === passEl) {
        fox.classList.add(pwShowing ? 'is-peek' : 'is-cover');
        eyeMode = pwShowing ? 'up' : 'cursor';
    } else if (active === pass2El) {
        fox.classList.add(pw2Showing ? 'is-peek' : 'is-cover');
        eyeMode = pw2Showing ? 'up' : 'cursor';
    } else if (active === nameEl || active === emailEl) {
        eyeMode = 'caret';
    } else {
        eyeMode = 'cursor';
    }
    kickEyes();
}

nameEl.addEventListener('focus',  applyFoxState);
nameEl.addEventListener('blur',   applyFoxState);
nameEl.addEventListener('input',  kickEyes);
emailEl.addEventListener('focus', applyFoxState);
emailEl.addEventListener('blur',  applyFoxState);
emailEl.addEventListener('input', kickEyes);
passEl.addEventListener('focus',  applyFoxState);
passEl.addEventListener('blur',   applyFoxState);
pass2El.addEventListener('focus', applyFoxState);
pass2El.addEventListener('blur',  applyFoxState);

passEl.addEventListener('input', () => {
    checkStrength(passEl.value);
});

reveal1.addEventListener('click', () => {
    const show = passEl.type === 'password';
    passEl.type = show ? 'text' : 'password';
    reveal1.classList.toggle('is-on', show);
    reveal1.setAttribute('aria-pressed', String(show));
    reveal1.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
    passEl.focus();
    applyFoxState();
});

reveal2.addEventListener('click', () => {
    const show = pass2El.type === 'password';
    pass2El.type = show ? 'text' : 'password';
    reveal2.classList.toggle('is-on', show);
    reveal2.setAttribute('aria-pressed', String(show));
    reveal2.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
    pass2El.focus();
    applyFoxState();
});

if (fine && !reduce) {
    addEventListener('pointermove', (e) => {
        const r = card.getBoundingClientRect();
        card.style.setProperty('--mx', `${((e.clientX - r.left) / r.width) * 100}%`);
        card.style.setProperty('--my', `${((e.clientY - r.top) / r.height) * 100}%`);
        stage.style.setProperty('--fox-x', `${(e.clientX / innerWidth - 0.5) * 10}px`);
        stage.style.setProperty('--fox-y', `${(e.clientY / innerHeight - 0.5) * 6}px`);
    }, { passive: true });
}

function checkStrength(val) {
    const fill = document.getElementById('strength-fill');
    const label = document.getElementById('strength-label');
    let score = 0;
    if (val.length >= 8) score++;
    if (val.length >= 12) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const levels = [
        { w: '20%', bg: '#ef4444', txt: 'Sangat lemah' },
        { w: '40%', bg: '#f97316', txt: 'Lemah' },
        { w: '60%', bg: '#eab308', txt: 'Cukup' },
        { w: '80%', bg: '#22c55e', txt: 'Kuat' },
        { w: '100%', bg: '#16a34a', txt: 'Sangat kuat' },
    ];
    const lvl = levels[Math.min(score - 1, 4)];
    if (val.length === 0) {
        fill.style.width = '0%';
        label.textContent = '';
    } else {
        fill.style.width = lvl.w;
        fill.style.background = lvl.bg;
        label.textContent = lvl.txt;
    }
}

const wait = (ms) => new Promise((r) => setTimeout(r, ms));
let submitting = false;

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    if (submitting) return;
    submitting = true;
    passEl.blur(); emailEl.blur(); nameEl.blur(); pass2El.blur();
    applyFoxState();
    btn.disabled = true;
    status.textContent = 'Membuat akun…';

    if (reduce) {
        btn.classList.add('dooropen');
        await wait(240);
    } else {
        btn.classList.add('dooropen');  await wait(380);
        btn.classList.add('walking');   await wait(60);
        btn.classList.add('out');       await wait(760);
        btn.classList.remove('walking', 'dooropen');
        await wait(360);
    }

    status.textContent = 'Akun dibuat!';
    fox.classList.remove('is-cover', 'is-peek');
    fox.classList.add('is-happy');

    await wait(1200);
    form.submit();
});
</script>
</body>
</html>