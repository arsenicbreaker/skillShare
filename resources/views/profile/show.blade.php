@extends('layouts.app')

@section('title', 'Profil - ' . $otherUser->name)

@section('content')
@php
    $xp = $otherUser->xpMeta();
    $ajarkan = $otherUser->userSkills->where('type', 'ajarkan');
    $pelajari = $otherUser->userSkills->where('type', 'pelajari');
@endphp

<div class="max-w-3xl mx-auto">
    <div class="surface rounded-[28px] overflow-hidden animate-fade-rise">

        <div class="px-8 pt-10 pb-8">
            <div class="flex flex-col items-center text-center">
                @if($otherUser->photo)
                    <img src="{{ Storage::url($otherUser->photo) }}"
                         class="h-28 w-28 rounded-full object-cover border border-white/10 shadow-2xl">
                @else
                    <div class="h-28 w-28 grid place-items-center rounded-full border border-white/10 bg-white/5 font-display text-4xl">
                        {{ strtoupper(mb_substr($otherUser->name, 0, 1)) }}
                    </div>
                @endif

                <h1 class="mt-6 font-display text-4xl tracking-tight">{{ $otherUser->name }}</h1>

                @if($otherUser->university)
                    <p class="mt-3 text-sm text-muted-foreground flex items-center gap-2">
                        <i class="bi bi-mortarboard"></i>
                        {{ $otherUser->major ?? '-' }} · Semester {{ $otherUser->semester ?? '-' }} · {{ $otherUser->university }}
                    </p>
                @endif
                @if($otherUser->city)
                    <p class="mt-1 text-sm text-muted-foreground flex items-center gap-2">
                        <i class="bi bi-geo-alt"></i> {{ $otherUser->city }}
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

            @if($otherUser->bio)
                <div class="mt-10 text-center">
                    <p class="text-xs uppercase tracking-[0.18em] text-muted-foreground mb-3">Tentang Saya</p>
                    <p class="text-muted-foreground leading-relaxed max-w-lg mx-auto">{{ $otherUser->bio }}</p>
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

            <!-- Kontak (baru terlihat kalau swap sudah diterima) -->
            @if($otherUser->whatsapp || $otherUser->discord || $otherUser->telegram)
                <div class="mt-8 text-center">
                    @if($existingRequest && $existingRequest->status === 'accepted')
                        <div class="flex justify-center gap-6 text-sm text-muted-foreground">
                            @if($otherUser->whatsapp)
                                <span class="flex items-center gap-1.5"><i class="bi bi-whatsapp text-green-400"></i> {{ $otherUser->whatsapp }}</span>
                            @endif
                            @if($otherUser->discord)
                                <span class="flex items-center gap-1.5"><i class="bi bi-discord text-indigo-300"></i> {{ $otherUser->discord }}</span>
                            @endif
                            @if($otherUser->telegram)
                                <span class="flex items-center gap-1.5"><i class="bi bi-telegram text-sky-300"></i> {{ $otherUser->telegram }}</span>
                            @endif
                        </div>
                    @else
                        <p class="text-muted-foreground text-sm flex items-center justify-center gap-1.5">
                            <i class="bi bi-lock"></i> Kontak terlihat setelah swap diterima
                        </p>
                    @endif
                </div>
            @endif

            <!-- Tombol / status swap request -->
            <div class="mt-10 flex flex-col items-center gap-3">
                @if(!$existingRequest)
                    @if($ajarkan->isNotEmpty())
                        @if($ajarkan->count() > 1)
                            <select id="swapSkillSelect" class="surface-soft rounded-full px-4 py-2 text-sm text-foreground outline-none">
                                @foreach($ajarkan as $us)
                                    <option value="{{ $us->skill->id }}" class="bg-[#061f2b]">{{ $us->skill->name }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="hidden" id="swapSkillSelect" value="{{ $ajarkan->first()->skill->id }}">
                        @endif

                        <button type="button" id="swapSendBtn"
                                data-receiver-id="{{ $otherUser->id }}"
                                class="rounded-full bg-white px-8 py-3.5 text-sm font-medium text-black flex items-center gap-2 transition hover:scale-[1.02] disabled:opacity-60 disabled:cursor-not-allowed">
                            <i class="bi bi-send"></i>
                            <span id="swapSendBtnText">Ajukan Swap</span>
                        </button>

                        <div id="swapError" class="hidden text-red-300 text-sm"></div>
                    @else
                        <p class="text-muted-foreground text-sm italic">User ini belum menambahkan skill yang bisa diajarkan.</p>
                    @endif
                @elseif($existingRequest->status === 'pending')
                    <span class="liquid-glass px-8 py-3.5 rounded-full font-medium flex items-center gap-2 text-amber-200">
                        <i class="bi bi-hourglass-split"></i> Menunggu Konfirmasi
                    </span>
                @elseif($existingRequest->status === 'accepted')
                    <span class="liquid-glass px-8 py-3.5 rounded-full font-medium flex items-center gap-2 text-green-300">
                        <i class="bi bi-check-circle"></i> Swap Diterima
                    </span>
                @elseif($existingRequest->status === 'rejected')
                    <span class="liquid-glass px-8 py-3.5 rounded-full font-medium flex items-center gap-2 text-red-300">
                        <i class="bi bi-x-circle"></i> Swap Ditolak
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@if(!$existingRequest && $ajarkan->isNotEmpty())
@section('scripts')
<script>
    document.getElementById('swapSendBtn')?.addEventListener('click', async function () {
        const btn = this;
        const btnText = document.getElementById('swapSendBtnText');
        const errBox = document.getElementById('swapError');
        const skillId = document.getElementById('swapSkillSelect')?.value;
        const receiverId = btn.dataset.receiverId;

        errBox.classList.add('hidden');

        if (!receiverId || !skillId) {
            errBox.textContent = 'Skill belum tersedia.';
            errBox.classList.remove('hidden');
            return;
        }

        btn.disabled = true;
        btnText.textContent = 'Mengirim...';

        try {
            const response = await fetch(@json(route('swap.send')), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': @json(csrf_token()),
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    receiver_id: parseInt(receiverId, 10),
                    skill_id: parseInt(skillId, 10),
                }),
            });

            const data = await response.json().catch(() => ({}));

            if (!response.ok || data.success === false) {
                const message = data.message
                    || data.errors?.skill_id?.[0]
                    || data.errors?.receiver_id?.[0]
                    || 'Gagal mengirim request.';
                errBox.textContent = message;
                errBox.classList.remove('hidden');
                btn.disabled = false;
                btnText.textContent = 'Ajukan Swap';
                return;
            }

            window.location.reload();
        } catch (error) {
            errBox.textContent = 'Terjadi kesalahan jaringan. Coba lagi.';
            errBox.classList.remove('hidden');
            btn.disabled = false;
            btnText.textContent = 'Ajukan Swap';
        }
    });
</script>
@endsection
@endif