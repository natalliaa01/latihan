<?php
// database/seeders/InstrukturSeeder.php

namespace Database\Seeders;

use App\Models\Instruktur;
use Illuminate\Database\Seeder;

class InstrukturSeeder extends Seeder
{
    public function run(): void
    {
        $instrukturs = [
            [
                'nama' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'bio' => 'Instruktur berpengalaman di bidang web development dengan 5 tahun pengalaman.',
                'keahlian' => 'Laravel, PHP, JavaScript'
            ],
            [
                'nama' => 'Sari Indah',
                'email' => 'sari@example.com',
                'bio' => 'Designer UI/UX dengan passion dalam menciptakan pengalaman pengguna yang menarik.',
                'keahlian' => 'UI/UX Design, Figma, Adobe XD'
            ],
            [
                'nama' => 'Agus Prasetyo',
                'email' => 'agus@example.com',
                'bio' => 'Data Scientist dan Machine Learning Engineer dengan background matematika.',
                'keahlian' => 'Python, Machine Learning, Data Analysis'
            ],
            [
                'nama' => 'Maya Putri',
                'email' => 'maya@example.com',
                'bio' => 'Mobile App Developer yang fokus pada pengembangan aplikasi Android dan iOS.',
                'keahlian' => 'Flutter, React Native, Kotlin'
            ]
        ];

        foreach ($instrukturs as $instruktur) {
            Instruktur::create($instruktur);
        }
    }
}