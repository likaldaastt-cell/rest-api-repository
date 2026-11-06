<?php

namespace App\Controllers;

use App\Models\Book;

class BookController
{
    private $book;

    public function __construct($db)
    {
        $this->book = new Book($db);
    }

    private function response($status, $data)
    {
        http_response_code($status);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    private function validate($data)
    {
        if (empty(trim($data['judul'] ?? ''))) {
            return 'Judul wajib diisi';
        }

        if (empty(trim($data['penulis'] ?? ''))) {
            return 'Penulis wajib diisi';
        }

        if (!isset($data['tahun']) || !is_numeric($data['tahun']) || $data['tahun'] <= 0) {
            return 'Tahun tidak valid';
        }

        if (!isset($data['stok']) || !is_numeric($data['stok']) || $data['stok'] < 0) {
            return 'Stok tidak valid';
        }

        return null;
    }

    public function index()
    {
        $books = $this->book->getAll();

        $this->response(200, [
            'success' => true,
            'data' => $books
        ]);
    }

    public function show($id)
    {
        $book = $this->book->getById($id);

        if (!$book) {
            $this->response(404, [
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ]);
            return;
        }

        $this->response(200, [
            'success' => true,
            'data' => $book
        ]);
    }

    public function store()
    {
        $data = json_decode(file_get_contents('php://input'), true) ?? [];

        $error = $this->validate($data);

        if ($error) {
            $this->response(400, [
                'success' => false,
                'message' => $error
            ]);
            return;
        }

        $book = $this->book->create($data);

        $this->response(201, [
            'success' => true,
            'message' => 'Buku berhasil ditambahkan',
            'data' => $book
        ]);
    }

    public function update($id)
    {
        $existing = $this->book->getById($id);

        if (!$existing) {
            $this->response(404, [
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ]);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true) ?? [];

        $error = $this->validate($data);

        if ($error) {
            $this->response(400, [
                'success' => false,
                'message' => $error
            ]);
            return;
        }

        $this->book->update($id, $data);

        $this->response(200, [
            'success' => true,
            'message' => 'Buku berhasil diperbarui',
            'data' => $this->book->getById($id)
        ]);
    }

    public function destroy($id)
    {
        $existing = $this->book->getById($id);

        if (!$existing) {
            $this->response(404, [
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ]);
            return;
        }

        $this->book->delete($id);

        $this->response(200, [
            'success' => true,
            'message' => 'Buku berhasil dihapus'
        ]);
    }
}