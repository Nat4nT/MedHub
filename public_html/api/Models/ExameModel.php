<?php

namespace Api\Models;

use PDO;

class ExameModel extends Model
{
    public $table = "exame";
    public $id_column_name = "exame_id";
    public $column_reference = "usuario_id";

    public function getUserExames($id_usuario)
    {
        $sql = "SELECT * FROM {$this->table} 
        INNER JOIN categoria_exame USING({$this->id_column_name}) 
        INNER JOIN categoria USING(categoria_id)
        WHERE {$this->table}.{$this->column_reference} = :id_usuario ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id_usuario", $id_usuario);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
