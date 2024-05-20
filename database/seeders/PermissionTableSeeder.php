<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'roles-list',
            'roles-create',
            'roles-edit',
            'roles-delete',
            'users-list',
            'users-create',
            'users-edit',
            'users-delete',
            'peserta-list',
            'peserta-create',
            'peserta-edit',
            'peserta-delete',
            'guru-list',
            'guru-create',
            'guru-edit',
            'guru-delete',
            'sesi-list',
            'sesi-create',
            'sesi-edit',
            'sesi-delete',
            'mapel-list',
            'mapel-create',
            'mapel-edit',
            'mapel-delete',
            'kelas-list',
            'kelas-create',
            'kelas-edit',
            'kelas-delete',
            'jurusan-list',
            'jurusan-create',
            'jurusan-edit',
            'jurusan-delete',
            'soal-list',
            'soal-create',
            'soal-edit',
            'soal-delete',
            'peserta-sesi-list',
            'peserta-sesi-create',
            'peserta-sesi-edit',
            'peserta-sesi-delete',
            'ujian-list',
            'ujian-create',
            'ujian-update',
            'ujian-delete',
            'ujian',
            'hasil-ujian',
            'nilai-ujian',
            'clear-cache'
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                [
                    'name' => $permission
                ],
                [
                    'name' => $permission
                ],
            );
        }
    }
}