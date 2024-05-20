<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            'guru',
            'siswa'
        ];

        foreach ($roles as $role){
            $create = Role::updateOrCreate([
                'name'  => $role
            ],[
                'name' => $role
            ]);
        }
    }
}
