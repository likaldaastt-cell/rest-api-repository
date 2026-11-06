<?php

namespace App\Repositories;

use App\Contracts\CategoryRepositoryInterface;
use PDO;

class MySQLCategoryRepository implements CategoryRepositoryInterface
{
    private $conn;
    private $table = 'categories';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $query = "SELECT * FROM {$this->table} ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $query = "INSERT INTO {$this->table} (nama, deskripsi)
                  VALUES (:nama, :deskripsi)";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':nama' => $data['nama'],
            ':deskripsi' => $data['deskripsi'] ?? ''
        ]);

        return $this->getById($this->conn->lastInsertId());
    }

    public function update($id, $data)
    {
        $query = "UPDATE {$this->table}
                  SET nama = :nama, deskripsi = :deskripsi
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':nama' => $data['nama'],
            ':deskripsi' => $data['deskripsi'] ?? '',
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        return $stmt->execute([':id' => $id]);
    }
}