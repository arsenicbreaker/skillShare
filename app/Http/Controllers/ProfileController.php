<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SwapRequest;
use App\Models\User;
use App\Models\UserSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('userSkills.skill.category');
        return view('profile.index', compact('user'));
    }

    public function edit()
    {
        $user       = Auth::user()->load('userSkills.skill.category');
        $categories = Category::with('skills')->get();
        return view('profile.edit', compact('user', 'categories'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'bio'        => 'nullable|string|max:500',
            'photo'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'university' => 'required|string|max:255',
            'major'      => 'required|string|max:255',
            'semester'   => 'required|integer|min:1|max:8',
            'city'       => 'nullable|string|max:255',
            'whatsapp'   => 'nullable|string|max:20',
            'discord'    => 'nullable|string|max:50',
            'telegram'   => 'nullable|string|max:50',
            'teach'      => 'required|array|min:1',
            'teach.*'    => 'exists:skills,id',
            'learn'      => 'required|array|min:1',
            'learn.*'    => 'exists:skills,id',
        ], [
            'name.required'       => 'Nama wajib diisi.',
            'university.required' => 'Nama kampus wajib diisi.',
            'major.required'      => 'Jurusan wajib diisi.',
            'semester.required'   => 'Semester wajib diisi.',
            'semester.min'        => 'Semester minimal 1.',
            'semester.max'        => 'Semester maksimal 8.',
            'city.max'            => 'Kota maksimal 255 karakter.',
            'photo.image'         => 'File harus berupa gambar.',
            'photo.mimes'         => 'Format foto harus jpg, jpeg, png, atau webp.',
            'photo.max'           => 'Ukuran foto maksimal 2MB.',
            'whatsapp.max'        => 'Nomor WhatsApp maksimal 20 karakter.',
            'discord.max'         => 'Username Discord maksimal 50 karakter.',
            'telegram.max'        => 'Username Telegram maksimal 50 karakter.',
            'teach.required'      => 'Pilih minimal 1 skill yang bisa kamu ajarkan.',
            'learn.required'      => 'Pilih minimal 1 skill yang ingin kamu pelajari.',
        ]);

        $user  = Auth::user();
        $teach = $request->teach ?? [];
        $learn = $request->learn ?? [];

        // Tolak jika ada skill yang sama di kedua daftar
        if (count(array_intersect($teach, $learn)) > 0) {
            return back()
                ->withErrors(['learn' => 'Skill yang sama tidak boleh dipilih sebagai "Ajarkan" dan "Pelajari".'])
                ->withInput();
        }

        $data = [
            'name'       => $request->name,
            'bio'        => $request->bio,
            'university' => $request->university,
            'major'      => $request->major,
            'semester'   => $request->semester,
            'city'       => $request->city,
            'whatsapp'   => $request->whatsapp,
            'discord'    => $request->discord,
            'telegram'   => $request->telegram,
        ];

        // Hapus foto lama sebelum upload baru
        if ($request->hasFile('photo')) {
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $user->update($data);

        $user->userSkills()->delete();

        foreach ($teach as $skillId) {
            UserSkill::create([
                'user_id'  => $user->id,
                'skill_id' => $skillId,
                'type'     => 'ajarkan',
            ]);
        }

        foreach ($learn as $skillId) {
            UserSkill::create([
                'user_id'  => $user->id,
                'skill_id' => $skillId,
                'type'     => 'pelajari',
            ]);
        }

        return redirect()->route('profile')->with('success', 'Profil berhasil diupdate!');
    }

    public function show($id)
    {
        $otherUser   = User::with('userSkills.skill.category')->findOrFail($id);
        $currentUser = Auth::user();

        if ($otherUser->id === $currentUser->id) {
            return redirect()->route('profile');
        }

        $existingRequest = SwapRequest::where('sender_id', $currentUser->id)
            ->where('receiver_id', $otherUser->id)
            ->first();

        return view('profile.show', compact('otherUser', 'existingRequest'));
    }
}