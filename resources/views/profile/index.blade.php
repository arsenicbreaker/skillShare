@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
@php
    $xp = $user->xpMeta();
    $ajarkan = $user->userSkills->where('type', 'ajarkan');
    $pelajari = $user->userSkills->where('type', 'pelajari');
@endphp

<div class="max-w-3xl mx-auto">
    <div class="surface rounded-[28px] overflow-hidden animate-fade-rise">

        <div class="px-8 pt-10 pb-8">
            <div class="flex flex-col items-center text-center">
                @if($user->photo)
                    <img src="{{ Storage::url($user->photo) }}"
                         class="h-28 w-28 rounded-full object-cover border border-white/10 shadow-2xl">
                @else
                    <div class="h-28 w-28 grid place-items-center rounded-full border border-white/10 bg-white/5 font-display text-4xl">
                        {{ strtoupper(mb_substr($user->name, 0, 1)) }}
                    </div>
                @endif

                <h1 class="mt-6 font-display text-4xl tracking-tight">{{ $user->name }}</h1>
                <p class="mt-1 text-sm text-muted-foreground">{{ $user->email }}</p>

                @if($user->university)
                    <p class="mt-3 text-sm text-muted-foreground flex items-center gap-2">
                        <i class="bi bi-mortarboard"></i>
                        {{ $user->major ?? '-' }} · Semester {{ $user->semester ?? '-' }} · {{ $user->university }}
                    </p>
                @endif
                @if($user->city)
                    <p class="mt-1 text-sm text-muted-foreground flex items-center gap-2">
                        <i class="bi bi-geo-alt"></i> {{ $user->city }}
                    </p>
                @endif
            </div>

            <!-- Badge -->
            <div class="flex justify-center mt-6">
                <div class="liquid-glass inline-flex items-center gap-2 rounded-full px-5 py-2 text-sm">
                    <i class="{{ $xp['badge']['icon'] ?? 'bi bi-person' }} text-white/80"></i>
                    <span class="font-medium">{{ $xp['badge']['nama'] ?? 'Newbie' }}</span>
                </div>
            </div>

            <!-- XP Progress -->
            <div class="max-w-sm mx-auto mt-6">
                <div class="flex justify-between text-sm text-muted-foreground mb-1.5">
                    <span class="text-foreground font-medium">Level {{ $xp['level'] }}</span>
                    <span>
                        {{ number_format($xp['xp']) }} XP
                        @if(!$xp['is_max_level'])
                            / {{ number_format($xp['next_level_xp']) }} XP ke Lv.{{ $xp['next_level'] }}
                        @else
                            (Maks)
                        @endif
                    </span>
                </div>
                <div class="h-1.5 w-full rounded-full bg-white/10 overflow-hidden">
                    <div class="h-full rounded-full bg-white/60" style="width: {{ $xp['xp_progress'] }}%"></div>
                </div>
            </div>

            @if($user->whatsapp || $user->discord || $user->telegram)
                <div class="flex justify-center gap-6 mt-8 text-sm text-muted-foreground">
                    @if($user->whatsapp)
                        <span class="flex items-center gap-1.5"><i class="bi bi-whatsapp text-green-400"></i> {{ $user->whatsapp }}</span>
                    @endif
                    @if($user->discord)
                        <span class="flex items-center gap-1.5"><i class="bi bi-discord text-indigo-300"></i> {{ $user->discord }}</span>
                    @endif
                    @if($user->telegram)
                        <span class="flex items-center gap-1.5"><i class="bi bi-telegram text-sky-300"></i> {{ $user->telegram }}</span>
                    @endif
                </div>
            @endif

            @if($user->bio)
                <div class="mt-10 text-center">
                    <p class="text-xs uppercase tracking-[0.18em] text-muted-foreground mb-3">Tentang Saya</p>
                    <p class="text-muted-foreground leading-relaxed max-w-lg mx-auto">{{ $user->bio }}</p>
                </div>
            @endif

            <!-- Skills -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-10">
                <div class="surface-soft rounded-2xl p-5">
                    <p class="text-xs uppercase tracking-[0.18em] text-muted-foreground mb-3 flex items-center gap-2">
                        <i class="bi bi-mortarboard-fill"></i> Bisa Diajarkan
                    </p>
                    <div class="flex flex-wrap gap-2">
                        @forelse($ajarkan as $us)
                            <span class="inline-flex rounded-full bg-white px-3 py-1 text-xs font-medium text-black">{{ $us->skill->name }}</span>
                        @empty
                            <p class="text-muted-foreground text-sm italic">Belum ada skill ditambahkan.</p>
                        @endforelse
                    </div>
                </div>

                <div class="surface-soft rounded-2xl p-5">
                    <p class="text-xs uppercase tracking-[0.18em] text-muted-foreground mb-3 flex items-center gap-2">
                        <i class="bi bi-book-fill"></i> Ingin Dipelajari
                    </p>
                    <div class="flex flex-wrap gap-2">
                        @forelse($pelajari as $us)
                            <span class="inline-flex rounded-full border border-white/15 px-3 py-1 text-xs text-foreground">{{ $us->skill->name }}</span>
                        @empty
                            <p class="text-muted-foreground text-sm italic">Belum ada skill ditambahkan.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="mt-10 flex justify-center">
                <a href="{{ route('profile.edit') }}"
                   class="rounded-full bg-white px-8 py-3.5 text-sm font-medium text-black flex items-center gap-2 transition hover:scale-[1.02]">
                    <i class="bi bi-pencil-square"></i> Edit Profil
                </a>
            </div>
        </div>
    </div>
</div>
@endsection