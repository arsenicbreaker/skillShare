@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
@php
    $teachIds = $user->userSkills->where('type', 'ajarkan')->pluck('skill_id')->toArray();
    $learnIds = $user->userSkills->where('type', 'pelajari')->pluck('skill_id')->toArray();
    $selectedTeachIds = collect(old('teach', $teachIds))->map(fn ($id) => (int) $id)->filter()->values()->all();
    $selectedLearnIds = collect(old('learn', $learnIds))->map(fn ($id) => (int) $id)->filter()->values()->all();
    $skillNames = $categories
        ->flatMap(fn ($category) => $category->skills)
        ->mapWithKeys(fn ($skill) => [(string) $skill->id => $skill->name])
        ->all();
@endphp

<style>
    .profile-skill-head {
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
        gap: 16px;
    }

    .profile-skill-head strong {
        color: white;
        font-weight: 600;
    }

    .profile-skill-search {
        position: relative;
        margin-bottom: 1rem;
    }

    .profile-skill-search input {
        width: 100%;
        padding: 10px 14px 10px 38px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        color: white;
        font-size: 0.875rem;
        outline: none;
        transition: border 0.2s;
    }

    .profile-skill-search input:focus {
        border-color: rgba(255, 255, 255, 0.3);
    }

    .profile-skill-search input::placeholder {
        color: rgba(255, 255, 255, 0.25);
    }

    .profile-skill-search .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(255, 255, 255, 0.3);
        font-size: 0.9rem;
        pointer-events: none;
    }

    .profile-skill-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 8px;
        max-height: 220px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: rgba(255, 255, 255, 0.12) transparent;
        padding-right: 4px;
        margin-bottom: 1rem;
    }

    .profile-skill-grid::-webkit-scrollbar {
        width: 4px;
    }

    .profile-skill-grid::-webkit-scrollbar-track {
        background: transparent;
    }

    .profile-skill-grid::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.12);
        border-radius: 9999px;
    }

    .profile-skill-chip {
        min-height: 42px;
        padding: 10px 8px;
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.04);
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.8rem;
        line-height: 1.25;
        text-align: center;
        cursor: pointer;
        transition: all 0.15s;
        user-select: none;
    }

    .profile-skill-chip:hover {
        background: rgba(255, 255, 255, 0.09);
        border-color: rgba(255, 255, 255, 0.2);
        color: white;
    }

    .profile-skill-chip.selected {
        background: rgba(255, 255, 255, 0.12);
        border-color: rgba(255, 255, 255, 0.4);
        color: white;
    }

    .profile-skill-chip.hidden {
        display: none;
    }

    .profile-selected-wrap {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        min-height: 28px;
        margin-bottom: 0.5rem;
    }

    .profile-selected-tag {
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

    .profile-selected-tag button {
        background: none;
        border: 0;
        color: rgba(255, 255, 255, 0.5);
        cursor: pointer;
        padding: 0;
        font-size: 0.8rem;
        line-height: 1;
        transition: color 0.15s;
    }

    .profile-selected-tag button:hover {
        color: white;
    }

    .profile-skill-divider {
        border: 0;
        border-top: 1px solid rgba(255, 255, 255, 0.07);
        margin: 1.75rem 0;
    }

    @media (max-width: 640px) {
        .profile-skill-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
</style>

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

                @error('teach')
                    <p class="mb-3 text-xs text-red-300">{{ $message }}</p>
                @enderror
                <div class="profile-skill-head">
                    <strong>Bisa saya ajarkan</strong>
                    <span>Pilih minimal 1</span>
                </div>

                <div class="profile-skill-search">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" placeholder="Cari skill..." data-skill-search="teach">
                </div>

                <div class="profile-skill-grid" id="teach-grid">
                    @foreach ($categories as $cat)
                        @foreach ($cat->skills as $skill)
                            <button
                                type="button"
                                class="profile-skill-chip {{ in_array($skill->id, $selectedTeachIds) ? 'selected' : '' }}"
                                data-id="{{ $skill->id }}"
                                data-name="{{ $skill->name }}"
                                data-section="teach"
                            >
                                {{ $skill->name }}
                            </button>
                        @endforeach
                    @endforeach
                </div>

                <div class="profile-selected-wrap" id="teach-tags"></div>
                <div id="teach-inputs"></div>

                <hr class="profile-skill-divider">

                @error('learn')
                    <p class="mb-3 text-xs text-red-300">{{ $message }}</p>
                @enderror
                <div class="profile-skill-head">
                    <strong>Ingin saya pelajari</strong>
                    <span>Pilih minimal 1</span>
                </div>

                <div class="profile-skill-search">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" placeholder="Cari skill..." data-skill-search="learn">
                </div>

                <div class="profile-skill-grid" id="learn-grid">
                    @foreach ($categories as $cat)
                        @foreach ($cat->skills as $skill)
                            <button
                                type="button"
                                class="profile-skill-chip {{ in_array($skill->id, $selectedLearnIds) ? 'selected' : '' }}"
                                data-id="{{ $skill->id }}"
                                data-name="{{ $skill->name }}"
                                data-section="learn"
                            >
                                {{ $skill->name }}
                            </button>
                        @endforeach
                    @endforeach
                </div>

                <div class="profile-selected-wrap" id="learn-tags"></div>
                <div id="learn-inputs"></div>
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

    const skillNames = @js($skillNames);
    const selected = { teach: {}, learn: {} };

    @js($selectedTeachIds).forEach(id => {
        if (skillNames[id]) selected.teach[id] = skillNames[id];
    });

    @js($selectedLearnIds).forEach(id => {
        if (skillNames[id] && !selected.teach[id]) selected.learn[id] = skillNames[id];
    });

    function toggleSkill(section, id, name, el) {
        if (selected[section][id]) {
            removeSkill(section, id);
        } else {
            const other = section === 'teach' ? 'learn' : 'teach';

            if (selected[other][id]) {
                showSkillError(`"${name}" sudah dipilih di bagian lain. Pilih skill yang berbeda.`);
                return;
            }

            selected[section][id] = name;
            el.classList.add('selected');
            hideSkillError();
        }

        renderTags(section);
        renderInputs(section);
    }

    function removeSkill(section, id) {
        delete selected[section][id];

        const chip = document.querySelector(`#${section}-grid .profile-skill-chip[data-id="${id}"]`);
        if (chip) chip.classList.remove('selected');

        renderTags(section);
        renderInputs(section);
    }

    function renderTags(section) {
        const wrap = document.getElementById(`${section}-tags`);
        wrap.innerHTML = '';

        Object.entries(selected[section]).forEach(([id, name]) => {
            const tag = document.createElement('div');
            const label = document.createElement('span');
            const removeButton = document.createElement('button');

            tag.className = 'profile-selected-tag';
            label.textContent = name;
            removeButton.type = 'button';
            removeButton.textContent = '×';
            removeButton.setAttribute('aria-label', `Hapus ${name}`);
            removeButton.addEventListener('click', () => removeSkill(section, id));

            tag.append(label, removeButton);
            wrap.appendChild(tag);
        });
    }

    function renderInputs(section) {
        const wrap = document.getElementById(`${section}-inputs`);
        wrap.innerHTML = '';

        Object.keys(selected[section]).forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `${section}[]`;
            input.value = id;
            wrap.appendChild(input);
        });
    }

    function filterSkills(section, query) {
        const normalizedQuery = query.toLowerCase().trim();

        document.querySelectorAll(`#${section}-grid .profile-skill-chip`).forEach(chip => {
            const name = chip.dataset.name.toLowerCase();
            chip.classList.toggle('hidden', normalizedQuery.length > 0 && !name.includes(normalizedQuery));
        });
    }

    function showSkillError(message) {
        const errBox = document.getElementById('skillError');
        errBox.textContent = message;
        errBox.classList.remove('hidden');
    }

    function hideSkillError() {
        const errBox = document.getElementById('skillError');
        errBox.textContent = '';
        errBox.classList.add('hidden');
    }

    document.querySelectorAll('.profile-skill-chip').forEach(chip => {
        chip.addEventListener('click', () => {
            toggleSkill(chip.dataset.section, chip.dataset.id, chip.dataset.name, chip);
        });
    });

    document.querySelectorAll('[data-skill-search]').forEach(input => {
        input.addEventListener('input', () => filterSkills(input.dataset.skillSearch, input.value));
    });

    document.querySelectorAll('.profile-skill-chip.selected').forEach(chip => {
        chip.classList.remove('selected');
    });

    ['teach', 'learn'].forEach(section => {
        Object.keys(selected[section]).forEach(id => {
            const chip = document.querySelector(`#${section}-grid .profile-skill-chip[data-id="${id}"]`);
            if (chip) chip.classList.add('selected');
        });

        renderTags(section);
        renderInputs(section);
    });

    document.getElementById('editProfileForm').addEventListener('submit', function (e) {
        const teachCount = Object.keys(selected.teach).length;
        const learnCount = Object.keys(selected.learn).length;

        if (teachCount < 1 || learnCount < 1) {
            e.preventDefault();
            showSkillError('Pilih minimal 1 skill untuk "Ajarkan" dan 1 skill untuk "Pelajari".');
            document.getElementById('skillError').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
</script>
@endsection
