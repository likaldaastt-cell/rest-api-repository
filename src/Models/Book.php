<?php

namespace App\Models;

use PDO;

class Book
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Mengambil semua data buku
    public function getAll()
    {
        $query = "SELECT * FROM books ORDER BY id ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mengambil buku berdasarkan ID
    public function getById($id)
    {
        $query = "SELECT * FROM books WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Menambahkan buku
    public function create($data)
    {
        $query = "INSERT INTO books (judul, penulis, tahun, stok)
                  VALUES (:judul, :penulis, :tahun, :stok)";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            'judul' => $data['judul'],
            'penulis' => $data['penulis'],
            'tahun' => $data['tahun'],
            'stok' => $data['stok']
        ]);
    }

    // Mengubah data buku
    public function update($id, $data)
    {
        $query = "UPDATE books
                  SET judul = :judul,
                      penulis = :penulis,
                      tahun = :tahun,
                      stok = :stok
                  WHERE id = :id";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            'id' => $id,
            'judul' => $data['judul'],
            'penulis' => $data['penulis'],
            'tahun' => $data['tahun'],
            'stok' => $data['stok']
        ]);
    }

    // Menghapus buku
    public function delete($id)
    {
        $query = "DELETE FROM books WHERE id = :id";
        $stmt = $this->db->prepare($query);

        return $stmt->execute(['id' => $id]);
    }
}