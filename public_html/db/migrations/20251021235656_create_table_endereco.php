<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CriaTabelaEndereco extends AbstractMigration
{

    public function change()
    {
        $table = $this->table('endereco', ['id' => 'endereco_id']);
        $table->addColumn('usuario_id', 'integer', ['limit' => 11])
            ->addColumn('cep', 'string', ['limit' => 9])
            ->addColumn('rua', 'string', ['limit' => 100])
            ->addColumn('numero', 'string', ['limit' => 10])
            ->addColumn('complemento', 'string', ['limit' => 150, 'default' => ' '])
            ->addColumn('bairro', 'string', ['limit' => 50])
            ->addColumn('cidade', 'string', ['limit' => 50])
            ->addColumn('estado', 'enum', [
                'values' => ['AC','AL','AP','AM','BA','CE',
                    'DF','ES','GO','MA','MT','MS','MG',
                    'PA','PB','PR','PE','PI','RJ','RN',
                    'RS','RO','RR','SC','SP','SE','TO'
                ]
            ])->addTimestamps()
            ->save();

            $this->table('endereco')
            ->addForeignKey('usuario_id', 'usuario', 'usuario_id', [
                'delete' => 'CASCADE',
                'update' => 'CASCADE'
            ])
            ->update();
    }
}
