<?php

namespace App\Models;
use PDO;

abstract class Model
{
    protected $conn;
    public $id = "0";
    public $table;
    public $id_column_name;


    public function __construct($id = 0)
    {
        $this->id = $id;
        // Define automaticamente o user_token com base na session
        if (session_status() === PHP_SESSION_NONE) {
            session_start(); // inicia a session, se ainda não foi
        }
        $this->conn = (new DB())->conn();
    }



    public function getData($append_column = "", $append_table = "", $where = '1=1', $oder_by = false, $sort = 'DESC', $limit = 20): array
    {
        if (!$oder_by) {
            $oder_by = $this->id_column_name;
        }

        if (!trim($where)) {
            $where = '1=1';
        }

        $sql = "SELECT {$this->table}.* {$append_column} FROM $this->table $append_table WHERE {$where} ORDER BY {$this->table}.$oder_by $sort LIMIT $limit";
        $stmt = $this->conn->prepare($sql);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Adiciona ao Banco o dado enviado
     */
    public function AddData(array $dados): int
    {
        if (isset($dados['files'])) {
            $dados['foto'] = $this->uploadImage($dados['files']);
            unset($dados['files']);
        }

        $colunas = array_keys($dados);

        $placeholders = array_map(function ($c) {
            return ":$c";
        }, $colunas);

        $sql = "INSERT INTO {$this->table} (" . implode(',', $colunas) . ") 
            VALUES (" . implode(',', $placeholders) . ")";

        $stmt = $this->conn->prepare($sql);

        foreach ($dados as $coluna => $valor) {
            $stmt->bindValue(":$coluna", $valor);
        }


        $stmt->execute();
        $id = $this->conn->lastInsertId();

        if ($stmt->rowCount()) {
            return $id;
        } else {
            return 0;
        }
    }

    public function editData(array $dados)
    {
        $params = [];
        $set = [];
        foreach ($dados as $col => $val) {
            $set[] = "`$col` = :$col";
            $params[$col] = $val;
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $set)
            . " WHERE {$this->id_column_name} = :{$this->id_column_name}";

        $params[$this->id_column_name] = $this->id;
        $stmt = $this->conn->prepare($sql);

        $stmt->execute($params);

        if ($stmt->rowCount()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteData(int $id): bool
    {
        $ref_column = $this->id_column_name;

        $sql = "DELETE FROM {$this->table} WHERE {$ref_column} = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id ?? $this->id);
        $stmt->execute();

        if ($stmt->rowCount()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Deleta fotos
     */
    public function deleteFile(object $object)
    {
        if (isset($object->foto)) {
            unlink(__DIR__ . '/../Views' . $object->foto);
        }
    }

    /**
     * Anexando foto a uma pasta local
     */
    public function uploadImage(array $file)
    {
        $table = $this->table;
        $subdir = explode('_', $table);
        $subdir = end($subdir);


        foreach ($file as $foto) {

            // Verifica se veio o arquivo
            if (!isset($foto['name']) || $foto['error'] !== UPLOAD_ERR_OK) {
                return false;
            }

            $uploadDir = __DIR__ . "/../../uploads/{$subdir}/";

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $extension = pathinfo($foto['name'], PATHINFO_EXTENSION);
            $filename = uniqid('img_', false) . '.' . strtolower($extension);

            $destination = $uploadDir . $filename;

            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!in_array(strtolower($extension), $allowedTypes)) {
                return false;
            }

            if (move_uploaded_file($foto['tmp_name'], $destination)) {

                // Retorna o link relativo da imagem
                return '/uploads/' . $subdir . "/" . $filename;
            }
        }

        return false; // Falha no upload
    }
}
