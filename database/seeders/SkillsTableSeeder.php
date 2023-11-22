<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $skills = [
            ['name' => "js"],
            ['name' => "php"],
            ['name' => "html"],
            ['name' => "css"]
        ];


        foreach($skills as $skill)
        {
            Skill::create($skill);
        }
    }
}
