<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\User;
use App\Models\UserSkill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name'=>'Budi Santoso','email'=>'budi@example.com','university'=>'Universitas Airlangga','major'=>'Informatika','city'=>'Surabaya','semester'=>6,'bio'=>'Suka ngoding dan berbagi ilmu.','xp'=>1250,'teach'=>['Laravel','PHP'],'learn'=>['Figma']],
            ['name'=>'Sari Rahayu','email'=>'sari@example.com','university'=>'ITS','major'=>'Desain Produk','city'=>'Surabaya','semester'=>5,'bio'=>'Designer yang gemar mengajarkan UI/UX.','xp'=>980,'teach'=>['Figma'],'learn'=>['Laravel']],
            ['name'=>'Andi Wijaya','email'=>'andi@example.com','university'=>'Universitas Brawijaya','major'=>'Teknik Informatika','city'=>'Malang','semester'=>7,'bio'=>'Developer mobile Android.','xp'=>750,'teach'=>['Java'],'learn'=>['Python']],
            ['name'=>'Fifif Susanti','email'=>'fifif@example.com','university'=>'Universitas Brawijaya','major'=>'Manajemen','city'=>'Malang','semester'=>4,'bio'=>'Tertarik di bidang bisnis.','xp'=>500,'teach'=>['PHP'],'learn'=>['Python']],
            ['name'=>'Dika Pratama','email'=>'dika@example.com','university'=>'ITS','major'=>'Sistem Informasi','city'=>'Surabaya','semester'=>3,'bio'=>'Suka eksplorasi teknologi baru.','xp'=>320,'teach'=>['JavaScript'],'learn'=>['Vue.js']],
            ['name'=>'Raka Nugraha','email'=>'raka@example.com','university'=>'Universitas Padjadjaran','major'=>'Ilmu Komputer','city'=>'Bandung','semester'=>5,'bio'=>'Passionate di bidang keamanan siber.','xp'=>290,'teach'=>['Python'],'learn'=>['Laravel']],
            ['name'=>'Layla Hidayah','email'=>'layla@example.com','university'=>'Universitas Airlangga','major'=>'Psikologi','city'=>'Surabaya','semester'=>4,'bio'=>'Suka riset dan menulis.','xp'=>240,'teach'=>['PHP'],'learn'=>['Python']],
            ['name'=>'Muhammad Fariz','email'=>'fariz@example.com','university'=>'Universitas Brawijaya','major'=>'Teknik Elektro','city'=>'Malang','semester'=>6,'bio'=>'Gemar IoT dan embedded systems.','xp'=>195,'teach'=>['C++'],'learn'=>['Python']],
            ['name'=>'Nadia Aisyah','email'=>'nadia@example.com','university'=>'ITS','major'=>'Arsitektur','city'=>'Surabaya','semester'=>3,'bio'=>'Arsitek muda ingin belajar desain digital.','xp'=>175,'teach'=>['PHP'],'learn'=>['Figma']],
            ['name'=>'Bagas Wicaksono','email'=>'bagas@example.com','university'=>'Universitas Padjadjaran','major'=>'Ilmu Komunikasi','city'=>'Bandung','semester'=>5,'bio'=>'Content creator ingin belajar web.','xp'=>160,'teach'=>['JavaScript'],'learn'=>['Laravel']],
        ];

        foreach ($users as $data) {
            if (User::where('email', $data['email'])->exists()) continue;

            $user = User::create([
                'name'         => $data['name'],
                'email'        => $data['email'],
                'password'     => Hash::make('password123'),
                'university'   => $data['university'],
                'major'        => $data['major'],
                'city'         => $data['city'],
                'semester'     => $data['semester'],
                'bio'          => $data['bio'],
                'xp'           => $data['xp'],
                'level'        => $this->resolveLevel($data['xp']),
                'is_onboarded' => true,
            ]);

            foreach ($data['teach'] as $skillName) {
                $skill = Skill::where('name', $skillName)->first();
                if ($skill) UserSkill::create(['user_id'=>$user->id,'skill_id'=>$skill->id,'type'=>'ajarkan']);
            }
            foreach ($data['learn'] as $skillName) {
                $skill = Skill::where('name', $skillName)->first();
                if ($skill) UserSkill::create(['user_id'=>$user->id,'skill_id'=>$skill->id,'type'=>'pelajari']);
            }
        }
    }

    private function resolveLevel(int $xp): int
    {
        return match(true) {
            $xp >= 10000 => 20, $xp >= 8000 => 18, $xp >= 6500 => 16,
            $xp >= 5000  => 14, $xp >= 3500 => 12, $xp >= 2500 => 10,
            $xp >= 1500  => 8,  $xp >= 800  => 6,  $xp >= 300  => 4,
            $xp >= 100   => 2,  default      => 1,
        };
    }
}