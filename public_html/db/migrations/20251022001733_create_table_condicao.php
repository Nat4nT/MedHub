<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTableCondicao extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table("condicao",['id'=>'condicao_id']);
        $table->addColumn('nome','string',['limit'=> 255,'null'=> false])
        ->addColumn('tipo','enum',['values'=>['alergia','doenca','deficiencia']])
        ->addTimestamps()
        ->create();

    }
}
