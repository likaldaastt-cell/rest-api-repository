<?php

namespace App\Config;

use PDO;
use PDOException;

class Database
{
    private $host = 'localhost';
    private $db_name = 'perpustakaan';
    private $username = 'root';
    private $password = '';

    public function connect()
    {
        try {
            $conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4",
                $this->username,
                $this->password
            );

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conn;
        } catch (PDOException $e) {
            die("Koneksi database gagal: " . $e->getMessage());
        }
    }
}