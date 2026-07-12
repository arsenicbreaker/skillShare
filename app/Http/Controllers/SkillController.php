<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index()
    {
        $categories = Category::with('skills')->get();
        return response()->json($categories);
    }

    public function search(Request $request)
    {
        $q = trim($request->q);

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $skills = Skill::with('category')
            ->where('name', 'like', "%{$q}%")
            ->orderBy('name')
            ->limit(20)
            ->get();

        return response()->json($skills);
    }
}