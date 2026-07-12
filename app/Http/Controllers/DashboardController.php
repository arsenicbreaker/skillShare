<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private const PER_PAGE = 12;

    public function index(Request $request)
    {
        $user = Auth::user();

        $learnSkillIds = $user->userSkills()
            ->where('type', 'pelajari')
            ->pluck('skill_id')
            ->toArray();

        $teachSkillIds = $user->userSkills()
            ->where('type', 'ajarkan')
            ->pluck('skill_id')
            ->toArray();

        $query = User::where('id', '!=', $user->id)
            ->where('is_onboarded', true)
            ->with(['userSkills.skill.category']);

        if ($request->filled('category')) {
            $query->whereHas('userSkills', function ($q) use ($request) {
                $q->where('type', 'ajarkan')
                  ->whereHas('skill', function ($q2) use ($request) {
                      $q2->where('category_id', $request->category);
                  });
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('userSkills.skill', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $totalPossible = count($learnSkillIds) + count($teachSkillIds);

        // Hitung persentase kecocokan untuk setiap user
        $allUsers = $query->get()->map(function ($otherUser) use ($learnSkillIds, $teachSkillIds, $totalPossible) {
            $otherTeachIds = $otherUser->userSkills
                ->where('type', 'ajarkan')
                ->pluck('skill_id')
                ->toArray();

            $otherLearnIds = $otherUser->userSkills
                ->where('type', 'pelajari')
                ->pluck('skill_id')
                ->toArray();

            $matchLearn = count(array_intersect($learnSkillIds, $otherTeachIds));
            $matchTeach = count(array_intersect($teachSkillIds, $otherLearnIds));

            $otherUser->match_percent = $totalPossible > 0
                ? round((($matchLearn + $matchTeach) / $totalPossible) * 100)
                : 0;

            return $otherUser;
        });

        // Urutkan berdasarkan kecocokan tertinggi
        $allUsers = $allUsers
            ->sortByDesc('match_percent')
            ->values();

        // Pagination manual setelah proses sorting
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $pagedUsers  = $allUsers->forPage($currentPage, self::PER_PAGE);

        $users = new LengthAwarePaginator(
            $pagedUsers,
            $allUsers->count(),
            self::PER_PAGE,
            $currentPage,
            [
                'path'  => $request->url(),
                'query' => $request->query(),
            ]
        );

        $categories = Category::orderBy('name')->get();

        return view('dashboard', compact('users', 'categories'));
    }
}