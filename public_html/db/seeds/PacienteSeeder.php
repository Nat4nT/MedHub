<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class PacienteSeeder extends AbstractSeed
{
    public function run(): void
    {
        $data = [
            [
                'paciente_id' => 2,
                'data_nascimento' => '1990-05-15',
                'peso' => 65.5,
                'altura' => 1.70,
                'desc_deficiencia' => null,
                'tipo_sanguineo' => 'O+',
                'alergias' => 'Amendoim, penicilina'
            ]
        ];

        $this->table('paciente')->insert($data)->saveData();
    }
}
