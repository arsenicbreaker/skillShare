<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Teknologi & Pemrograman'],
            ['name' => 'Desain Kreatif'],
            ['name' => 'Data & AI'],
            ['name' => 'Bahasa'],
            ['name' => 'Bisnis & Marketing'],
            ['name' => 'Musik & Audio'],
            ['name' => 'Fotografi & Video'],
            ['name' => 'Seni & Kreativitas'],
            ['name' => 'Olahraga'],
            ['name' => 'Soft Skill'],
            ['name' => 'Akademik'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}