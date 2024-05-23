<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Major;

class MajorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'ENGLISH FOR SENIOR HIGH',
            'ENGLISH FOR COLLEGE STUDENT AND PUBLIC',
            'GRAPHIC DESIGN',
            'OFFICE APPLICATIONS',
        ];

        foreach ($data as $item) {
            Major::create([
                'major' => $item
            ]);
        }
    }
}