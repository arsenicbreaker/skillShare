<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Models\UserSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OnboardingController extends Controller
{
    /**
     * Step 1 - Lengkapi Profil
     */
    public function step1()
    {
        if (Auth::user()->is_onboarded) {
            return redirect()->route('dashboard');
        }

        $user = Auth::user();
        return view('onboarding.step1', compact('user'));
    }

    public function step1Save(Request $request)
    {
        if (Auth::user()->is_onboarded) {
            return redirect()->route('dashboard');
        }

        $request->validate([
            'name' => 'required|string|min:3|max:255',
            'photo'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'university' => 'required|string|max:255',
            'major'      => 'required|string|max:255',
            'semester'   => 'required|integer|min:1|max:8',
            'city'       => 'nullable|string|max:255',
            'bio'        => 'nullable|string|max:500',
            'whatsapp'   => 'nullable|digits_between:10,13',
            'discord'    => 'nullable|string|max:50',
            'telegram'   => 'nullable|string|max:50',
        ], [
            'name.required'       => 'Nama wajib diisi.',
            'name.min'            => 'Nama minimal 3 karakter.',
            'name.max'            => 'Nama maksimal 255 karakter.',
            'photo.image'         => 'File harus berupa gambar.',
            'photo.mimes'         => 'Format foto harus jpg, jpeg, png, atau webp.',
            'photo.max'           => 'Ukuran foto maksimal 2MB.',
            'university.required' => 'Nama kampus wajib diisi.',
            'major.required'      => 'Jurusan wajib diisi.',
            'semester.required'   => 'Semester wajib diisi.',
            'semester.min'        => 'Semester minimal 1.',
            'semester.max'        => 'Semester maksimal 8.',
            'city.max'            => 'Kota maksimal 255 karakter.',
            'bio.max'             => 'Bio maksimal 500 karakter.',
            'whatsapp.digits_between' => 'Nomor WhatsApp harus 10-13 digit angka.',
            'discord.max'         => 'Username Discord maksimal 50 karakter.',
            'telegram.max'        => 'Username Telegram maksimal 50 karakter.',
        ]);

        $user = Auth::user();

        $data = [
            'name'       => $request->name,
            'university' => $request->university,
            'major'      => $request->major,
            'semester'   => $request->semester,
            'city'       => $request->city,
            'bio'        => $request->bio,
            'whatsapp'   => $request->whatsapp,
            'discord'    => $request->discord,
            'telegram'   => $request->telegram,
        ];

        if ($request->hasFile('photo')) {
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $user->update($data);

        return redirect()->route('onboarding.step2');
    }

    /**
     * Step 2 - Pilih Keahlian
     */
    public function step2()
    {
        if (Auth::user()->is_onboarded) {
            return redirect()->route('dashboard');
        }

        $categories = Category::with('skills')->get();
        return view('onboarding.step2', compact('categories'));
    }

    public function step2Save(Request $request)
    {
        if (Auth::user()->is_onboarded) {
            return redirect()->route('dashboard');
        }

        $request->validate([
            'teach'   => 'required|array|min:1',
            'teach.*' => 'exists:skills,id',
            'learn'   => 'required|array|min:1',
            'learn.*' => 'exists:skills,id',
        ], [
            'teach.required' => 'Pilih minimal 1 skill yang bisa kamu ajarkan.',
            'teach.min'      => 'Pilih minimal 1 skill yang bisa kamu ajarkan.',
            'teach.*.exists' => 'Skill tidak valid.',
            'learn.required' => 'Pilih minimal 1 skill yang ingin kamu pelajari.',
            'learn.min'      => 'Pilih minimal 1 skill yang ingin kamu pelajari.',
            'learn.*.exists' => 'Skill tidak valid.',
        ]);

        $user  = Auth::user();
        $teach = $request->teach ?? [];
        $learn = $request->learn ?? [];

        if (count(array_intersect($teach, $learn)) > 0) {
            return back()
                ->withErrors(['learn' => 'Skill yang sama tidak boleh dipilih sebagai "Ajarkan" dan "Pelajari".'])
                ->withInput();
        }

        foreach ($teach as $skillId) {
            UserSkill::updateOrCreate(
                ['user_id' => $user->id, 'skill_id' => $skillId],
                ['type' => 'ajarkan']
            );
        }

        foreach ($learn as $skillId) {
            UserSkill::updateOrCreate(
                ['user_id' => $user->id, 'skill_id' => $skillId],
                ['type' => 'pelajari']
            );
        }

        if (!$user->is_onboarded) {
            $user->update(['is_onboarded' => true]);
            $user->addXp(User::XP_ONBOARDING);
        }

        return redirect()->route('dashboard');
    }
}