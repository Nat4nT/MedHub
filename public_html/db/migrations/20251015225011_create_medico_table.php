<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateMedicoTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('medico', [
            'id' => false,
            'primary_key' => ['medico_id']
        ]);

        $table->addColumn('medico_id', 'integer', ['signed' => false,'null'=>false])
              ->addColumn('especialidade', 'string', ['limit' => 500])
              ->addColumn('crm', 'string', ['limit' => 10])
              ->addColumn('estado_atuacao', 'enum', [
                  'values' => [
                      'AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO',
                      'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI',
                      'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'
                  ],
                  'null'=> false
              ])
              ->save();

        // Adiciona a chave estrangeira ligando medico.medico_id a usuario.usuario_id
        $this->table('medico')
            ->addForeignKey('medico_id', 'usuario', 'usuario_id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION'
            ])
            ->update();
    }
}
