<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompetenceSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('competences')->truncate();

        $competences = [
            [
                'name'        => 'Bloc A — Clarté du projet professionnel',
                'description' => 'Vérifier si le bénéficiaire sait où il va.',
                'order'       => 1,
            ],
            [
                'name'        => 'Bloc B — Motivation & engagement',
                'description' => 'Mesurer la volonté réelle d\'insertion.',
                'order'       => 2,
            ],
            [
                'name'        => 'Bloc C — Compétences & savoir-faire',
                'description' => 'Apprécier le niveau technique actuel.',
                'order'       => 3,
            ],
            [
                'name'        => 'Bloc D — Soft skills & posture professionnelle',
                'description' => 'Évaluer le comportement professionnel.',
                'order'       => 4,
            ],
            [
                'name'        => 'Bloc E — Autonomie & préparation à l\'insertion',
                'description' => 'Mesurer la capacité à agir seul.',
                'order'       => 5,
            ],
        ];

        foreach ($competences as $c) {
            DB::table('competences')->insert(array_merge($c, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}