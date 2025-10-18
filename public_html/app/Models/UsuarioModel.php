<?php

namespace App\Models;

use PDO;

class UsuarioModel extends Model
{
    public $table = 'usuario';
    public $id_column_name = 'id_usuario';

    public function buscarPorEmail($email)
    {
        $sql = "SELECT usuario_id, senha, tipo_usuario FROM {$this->table} WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":email", $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}
