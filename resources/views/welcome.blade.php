<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SkillShare</title>

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
                },
            },
        };
    </script>

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

        * {
            box-sizing: border-box;
        }

        html {
            min-height: 100%;
            background: hsl(var(--background));
        }

        body {
            margin: 0;
            min-height: 100vh;
            overflow-x: hidden;
            background: hsl(var(--background));
            color: hsl(var(--foreground));
            font-family: var(--font-body);
        }

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

        @keyframes fade-rise {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-rise { animation: fade-rise 0.8s ease-out both; }
        .animate-fade-rise-delay { animation: fade-rise 0.8s ease-out 0.2s both; }
        .animate-fade-rise-delay-2 { animation: fade-rise 0.8s ease-out 0.4s both; }
    </style>
</head>
<body>
    <div class="relative min-h-screen overflow-hidden">
        <video
            class="absolute inset-0 z-0 h-full w-full object-cover"
            autoplay
            loop
            muted
            playsinline
            aria-hidden="true"
        >
            <source src="https://d8j0ntlcm91z4.cloudfront.net/user_38xzZboKViGWJOttwIXH07lWA1P/hf_20260314_131748_f2ca2a28-fed7-44c8-b9a9-bd9acdd5ec31.mp4" type="video/mp4">
        </video>

        <header class="relative z-10">
            <nav class="mx-auto flex max-w-7xl flex-row items-center justify-between px-8 py-6">
                <a
                    href="#"
                    class="text-3xl tracking-tight text-foreground"
                    style="font-family: 'Instrument Serif', serif;"
                >
                    SkillShare<sup class="text-xs">®</sup>
                </a>

                <div class="hidden items-center gap-8 md:flex">
                    <a href="#" class="text-sm text-foreground transition-colors hover:text-foreground">Home</a>
                    <a href="#" class="text-sm text-muted-foreground transition-colors hover:text-foreground">How It Works</a>
                    <a href="#" class="text-sm text-muted-foreground transition-colors hover:text-foreground">Skills</a>
                    <a href="#" class="text-sm text-muted-foreground transition-colors hover:text-foreground">Testimonials</a>
                    <a href="#" class="text-sm text-muted-foreground transition-colors hover:text-foreground">Reach Us</a>
                </div>

                <button
                    type="button"
                    class="liquid-glass rounded-full px-6 py-2.5 text-sm font-medium text-foreground transition duration-300 hover:scale-[1.03]"
                >
                    Login
                </button>
            </nav>
        </header>

        <main class="relative z-10 flex min-h-[calc(100vh-96px)] flex-col items-center justify-center text-center">
            <section class="flex w-full flex-col items-center px-6 pb-40 pt-32 py-[90px]">
                <h1
                    class="animate-fade-rise max-w-7xl text-5xl font-normal leading-[0.95] tracking-[-2.46px] text-foreground sm:text-7xl md:text-8xl"
                    style="font-family: 'Instrument Serif', serif;"
                >
                    Where <em class="not-italic text-muted-foreground">dreams</em> rise
                    through <em class="not-italic text-muted-foreground">the silence.</em>
                </h1>

                <p class="animate-fade-rise-delay mt-8 max-w-2xl text-base leading-relaxed text-muted-foreground sm:text-lg">
                    We're designing tools for deep thinkers, bold creators, and quiet rebels. Amid the chaos, we build digital spaces for sharp focus and inspired work.
                </p>

                <button
                    type="button"
                    class="liquid-glass animate-fade-rise-delay-2 mt-12 cursor-pointer rounded-full px-14 py-5 text-base font-medium text-foreground transition duration-300 hover:scale-[1.03]"
                >
                    Discover More
                </button>
            </section>
        </main>
    </div>
</body>
</html>
