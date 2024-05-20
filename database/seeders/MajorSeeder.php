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
            'TKJ',
            'RPL',
            'AKUTANSI'
        ];

        foreach ($data as $item){
            Major::create([
                'major' => $item
            ]);
        }
    }
}
