<?php

declare(strict_types=1);

use Phinx\Db\Action\AddColumn;
use Phinx\Migration\AbstractMigration;

final class CreatePacienteTable extends AbstractMigration
{

    public function change(): void
    {
        $table = $this->table("paciente", [
            "id" => false,
            "primary_key" => ['paciente_id']
        ]);
        $table->addColumn('paciente_id', 'integer', ['signed' => false,'null'=>false])
            ->addColumn('data_nascimento', 'date', ['null' => false])
            ->AddColumn('peso', 'decimal', ['precision' => 5, 'scale' => 2, 'null' => true, 'default' => null])
            ->AddColumn('altura', 'decimal', ['precision' => 3, 'scale' => 2, 'null' => true, 'default' => null])
            ->addColumn('desc_deficiencia', 'string', ['limit' => 500, 'default' => null])
            ->addColumn('tipo_sanguineo', 'enum', ['values' => ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'], 'default' => null])
            ->addColumn('alergias', 'string', ['limit' => 500, 'default' => null])
            ->addColumn('doencas_diagnosticadas', 'string', ['limit' => 500, 'default' => null])
            ->save();

        $this->table('paciente')
            ->addForeignKey('paciente_id', 'usuario', 'usuario_id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION'
            ])
            ->update();
    }
}
