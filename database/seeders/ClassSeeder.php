<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassRoom;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'Bahasa Inggris',
            'Komputer',
        ];

        foreach ($data as $item) {

            ClassRoom::create([
                'name' => $item
            ]);
        }
    }
}