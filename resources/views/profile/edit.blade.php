@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
@php
    $teachIds = $user->userSkills->where('type', 'ajarkan')->pluck('skill_id')->toArray();
    $learnIds = $user->userSkills->where('type', 'pelajari')->pluck('skill_id')->toArray();
@endphp

<div class="max-w-2xl mx-auto">
    <div class="surface rounded-[28px] p-8 md:p-10 animate-fade-rise">
        <h2 class="font-display text-4xl text-center mb-8">Edit Profil</h2>

        @if ($errors->any())
            <div class="surface-soft rounded-2xl p-4 mb-6 text-sm text-red-300">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6" id="editProfileForm">
            @csrf

            <!-- Foto -->
            <div class="flex flex-col items-center">
                @if($user->photo)
                    <img id="photoPreview" src="{{ Storage::url($user->photo) }}"
                         class="h-28 w-28 rounded-full object-cover border border-white/10 mb-4">
                @else
                    <div id="photoPreviewInitial" class="h-28 w-28 grid place-items-center rounded-full border border-white/10 bg-white/5 font-display text-4xl mb-4">
                        {{ strtoupper(mb_substr($user->name, 0, 1)) }}
                    </div>
                    <img id="photoPreview" src="" class="h-28 w-28 rounded-full object-cover border border-white/10 mb-4 hidden">
                @endif

                <label class="surface-soft cursor-pointer rounded-full px-4 py-2 text-xs text-muted-foreground hover:text-foreground transition">
                    <i class="bi bi-upload"></i> Pilih foto
                    <input type="file" name="photo" id="photo" accept="image/png,image/jpeg,image/webp" class="hidden">
                </label>
                <p class="text-xs text-muted-foreground mt-2">jpg/jpeg/png/webp, maksimal 2MB</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm text-muted-foreground mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="surface-soft w-full rounded-2xl px-4 py-3 text-sm text-foreground outline-none focus:border-white/25" required>
                </div>
                <div>
                    <label class="block text-sm text-muted-foreground mb-2">Email</label>
                    <input type="email" value="{{ $user->email }}" disabled
                           class="surface-soft w-full rounded-2xl px-4 py-3 text-sm text-muted-foreground cursor-not-allowed">
                    <p class="text-xs text-muted-foreground mt-1">Email tidak bisa diubah lewat form ini.</p>
                </div>
            </div>

            <div>
                <label class="block text-sm text-muted-foreground mb-2">Bio</label>
                <textarea name="bio" rows="4" maxlength="500"
                          class="surface-soft w-full resize-none rounded-2xl px-4 py-3 text-sm text-foreground outline-none focus:border-white/25">{{ old('bio', $user->bio) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm text-muted-foreground mb-2">Universitas <span class="text-red-400">*</span></label>
                    <input type="text" name="university" value="{{ old('university', $user->university) }}"
                           class="surface-soft w-full rounded-2xl px-4 py-3 text-sm text-foreground outline-none focus:border-white/25" required>
                </div>
                <div>
                    <label class="block text-sm text-muted-foreground mb-2">Jurusan <span class="text-red-400">*</span></label>
                    <input type="text" name="major" value="{{ old('major', $user->major) }}"
                           class="surface-soft w-full rounded-2xl px-4 py-3 text-sm text-foreground outline-none focus:border-white/25" required>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm text-muted-foreground mb-2">Semester <span class="text-red-400">*</span></label>
                    <input type="number" name="semester" min="1" max="8" value="{{ old('semester', $user->semester) }}"
                           class="surface-soft w-full rounded-2xl px-4 py-3 text-sm text-foreground outline-none focus:border-white/25" required>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm text-muted-foreground mb-2">Kota</label>
                    <input type="text" name="city" value="{{ old('city', $user->city) }}"
                           class="surface-soft w-full rounded-2xl px-4 py-3 text-sm text-foreground outline-none focus:border-white/25">
                </div>
            </div>

            <div>
                <label class="block text-sm text-muted-foreground mb-3">Kontak</label>
                <div class="space-y-3">
                    <input type="text" name="whatsapp" placeholder="WhatsApp" value="{{ old('whatsapp', $user->whatsapp) }}"
                           class="surface-soft w-full rounded-2xl px-4 py-3 text-sm text-foreground outline-none focus:border-white/25">
                    <input type="text" name="discord" placeholder="Discord" value="{{ old('discord', $user->discord) }}"
                           class="surface-soft w-full rounded-2xl px-4 py-3 text-sm text-foreground outline-none focus:border-white/25">
                    <input type="text" name="telegram" placeholder="Telegram" value="{{ old('telegram', $user->telegram) }}"
                           class="surface-soft w-full rounded-2xl px-4 py-3 text-sm text-foreground outline-none focus:border-white/25">
                </div>
            </div>

            <!-- Skill -->
            <div>
                <div class="flex items-center justify-between mb-3">
                    <label class="block text-sm text-muted-foreground">Pilih Skill <span class="text-red-400">*</span></label>
                    <span class="text-xs text-muted-foreground">Satu skill hanya boleh dipilih di salah satu kolom</span>
                </div>

                <div id="skillError" class="hidden surface-soft rounded-2xl p-3 mb-3 text-sm text-red-300"></div>

                <div class="surface-soft rounded-2xl overflow-hidden">
                    <div class="grid grid-cols-3 text-xs font-semibold text-muted-foreground uppercase px-4 py-3 border-b border-white/10">
                        <span>Skill</span>
                        <span class="text-center">Ajarkan</span>
                        <span class="text-center">Pelajari</span>
                    </div>
                    <div class="max-h-80 overflow-y-auto divide-y divide-white/5">
                        @foreach($categories as $category)
                            <div class="px-4 py-2 text-sm font-semibold text-muted-foreground bg-white/2">
                                {{ $category->name }}
                            </div>
                            @foreach($category->skills as $skill)
                                <div class="grid grid-cols-3 items-center px-4 py-2.5">
                                    <span class="text-sm text-foreground">{{ $skill->name }}</span>
                                    <div class="text-center">
                                        <input type="checkbox" name="teach[]" value="{{ $skill->id }}"
                                               class="teach-checkbox w-4 h-4 accent-white"
                                               data-skill="{{ $skill->id }}"
                                               {{ in_array($skill->id, old('teach', $teachIds)) ? 'checked' : '' }}>
                                    </div>
                                    <div class="text-center">
                                        <input type="checkbox" name="learn[]" value="{{ $skill->id }}"
                                               class="learn-checkbox w-4 h-4 accent-white"
                                               data-skill="{{ $skill->id }}"
                                               {{ in_array($skill->id, old('learn', $learnIds)) ? 'checked' : '' }}>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="pt-4 flex items-center justify-between gap-4">
                <a href="{{ route('profile') }}" class="text-sm text-muted-foreground hover:text-foreground transition">Batal</a>
                <button type="submit" class="rounded-full bg-white px-8 py-3.5 text-sm font-medium text-black flex items-center gap-2 transition hover:scale-[1.02]">
                    <i class="bi bi-check2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('photo').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const preview = document.getElementById('photoPreview');
        const initial = document.getElementById('photoPreviewInitial');

        preview.src = URL.createObjectURL(file);
        preview.classList.remove('hidden');
        if (initial) initial.classList.add('hidden');
    });

    document.querySelectorAll('.teach-checkbox').forEach(cb => {
        cb.addEventListener('change', function () {
            if (this.checked) {
                const pair = document.querySelector(`.learn-checkbox[data-skill="${this.dataset.skill}"]`);
                if (pair) pair.checked = false;
            }
        });
    });
    document.querySelectorAll('.learn-checkbox').forEach(cb => {
        cb.addEventListener('change', function () {
            if (this.checked) {
                const pair = document.querySelector(`.teach-checkbox[data-skill="${this.dataset.skill}"]`);
                if (pair) pair.checked = false;
            }
        });
    });

    document.getElementById('editProfileForm').addEventListener('submit', function (e) {
        const teachCount = document.querySelectorAll('.teach-checkbox:checked').length;
        const learnCount = document.querySelectorAll('.learn-checkbox:checked').length;
        const errBox = document.getElementById('skillError');

        if (teachCount < 1 || learnCount < 1) {
            e.preventDefault();
            errBox.textContent = 'Pilih minimal 1 skill untuk "Ajarkan" dan 1 skill untuk "Pelajari".';
            errBox.classList.remove('hidden');
            errBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
</script>
@endsection