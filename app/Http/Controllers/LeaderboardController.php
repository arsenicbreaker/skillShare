<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LeaderboardController extends Controller
{
    public function index()
    {
        $authUser = Auth::user();

        $makeInitials = function (?string $name): string {
            $parts = preg_split('/\s+/', trim((string) $name)) ?: [];
            return collect($parts)->filter()->take(2)
                ->map(fn($p) => strtoupper(mb_substr($p, 0, 1)))->implode('');
        };

        $photoUrl = function (?string $photo): ?string {
            if (!filled($photo)) return null;
            if (Storage::disk('public')->exists($photo)) return asset('storage/' . ltrim($photo, '/'));
            if (str_starts_with($photo, 'http://') || str_starts_with($photo, 'https://')) return $photo;
            return asset('storage/' . ltrim($photo, '/'));
        };

        // Ambil semua user yang sudah onboarding, urutkan by XP
        $allUsers = User::where('is_onboarded', true)
            ->orderByDesc('xp')
            ->get()
            ->values()
            ->map(fn($u) => array_merge($u->xpMeta(), [
                'id'        => $u->id,
                'name'      => $u->name,
                'initials'  => $makeInitials($u->name),
                'photo_url' => $photoUrl($u->photo),
                'university'=> $u->university ?? 'Kampus belum diisi',
                'major'     => $u->major ?? '',
            ]));

        // Cari rank user yang login
        $myRank = $allUsers->search(fn($u) => $u['id'] === $authUser->id);
        $myRank = $myRank !== false ? $myRank + 1 : '-';

        $myXpMeta = $authUser->xpMeta();

        return view('leaderboard', [
            'leaderboard' => $allUsers->take(10),
            'myRank'      => $myRank,
            'myXpMeta'    => $myXpMeta,
        ]);
    }
}