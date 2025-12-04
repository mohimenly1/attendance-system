<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classroom;

class ClassroomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            ['name' => '310',  'capacity' => 40],
            ['name' => '308',  'capacity' => 40],
            ['name' => '309',  'capacity' => 30],
            ['name' => '306', 'capacity' => 25],
            ['name' => '304', 'capacity' => 25],
            ['name' => '305','capacity' => 60],
        ];

        foreach ($rooms as $r) {
            Classroom::firstOrCreate(
                ['name' => $r['name']],
                ['capacity' => $r['capacity']]
            );
        }
    }
}
