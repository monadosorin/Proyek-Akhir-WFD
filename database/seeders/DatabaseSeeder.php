<?php

namespace Database\Seeders;

use App\Models\Slot;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Prof. Andre Setiawan',
            'email' => 'kaprodi@petra.ac.id',
            'password' => 'password',
            'role' => 'kaprodi',
            'nrp_nip' => '197501012000031001',
            'prodi' => 'Informatika',
        ]);

        $dosen = User::create([
            'name' => 'Dr. Budi Santoso',
            'email' => 'dosen@petra.ac.id',
            'password' => 'password',
            'role' => 'dosen',
            'nrp_nip' => '198001012005011001',
            'prodi' => 'Informatika',
            'quota' => 10,
        ]);

        User::create([
            'name' => 'Andi Wijaya',
            'email' => 'mahasiswa@petra.ac.id',
            'password' => 'password',
            'role' => 'mahasiswa',
            'nrp_nip' => 'C14230115',
            'prodi' => 'Informatika',
            'dosen_pembimbing_id' => $dosen->id,
        ]);

        foreach ([1, 2, 3] as $offset) {
            Slot::create([
                'dosen_id' => $dosen->id,
                'tanggal' => now()->addDays($offset)->toDateString(),
                'jam_mulai' => '09:00',
                'jam_selesai' => '10:00',
                'status' => 'tersedia',
                'keterangan' => 'Slot bimbingan reguler',
            ]);
        }
    }
}
