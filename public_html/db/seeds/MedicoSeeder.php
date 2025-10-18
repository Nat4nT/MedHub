<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class MedicoSeeder extends AbstractSeed
{
    public function run(): void
    {
        $data = [
            [
                'medico_id' => 1, // corresponde ao usuario_id = 1
                'especialidade' => 'Cardiologia',
                'crm' => 'CRM1234567',
                'estado_atuacao' => 'SP'
            ]
        ];

        $this->table('medico')->insert($data)->saveData();
    }
}
