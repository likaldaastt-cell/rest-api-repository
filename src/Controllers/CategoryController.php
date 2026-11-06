<?php

namespace App\Controllers;

use App\Services\CategoryService;

class CategoryController
{
    private $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    private function response($success, $message, $data = null, $status = 200)
    {
        http_response_code($status);

        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ]);
    }

    public function index()
    {
        $this->response(
            true,
            'Daftar kategori',
            $this->service->getAll()
        );
    }

    public function show($id)
    {
        $category = $this->service->getById($id);

        if (!$category) {
            $this->response(false, 'Kategori tidak ditemukan', null, 404);
            return;
        }

        $this->response(true, 'Detail kategori', $category);
    }

    public function store()
    {
        $data = json_decode(file_get_contents('php://input'), true) ?? [];

        if (empty($data['nama'])) {
            $this->response(false, 'Nama kategori wajib diisi', null, 400);
            return;
        }

        $category = $this->service->create($data);

        $this->response(true, 'Kategori berhasil dibuat', $category, 201);
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents('php://input'), true) ?? [];

        if (empty($data['nama'])) {
            $this->response(false, 'Nama kategori wajib diisi', null, 400);
            return;
        }

        $category = $this->service->getById($id);

        if (!$category) {
            $this->response(false, 'Kategori tidak ditemukan', null, 404);
            return;
        }

        $this->service->update($id, $data);

        $this->response(
            true,
            'Kategori berhasil diperbarui',
            $this->service->getById($id)
        );
    }

    public function destroy($id)
    {
        $category = $this->service->getById($id);

        if (!$category) {
            $this->response(false, 'Kategori tidak ditemukan', null, 404);
            return;
        }

        $this->service->delete($id);

        $this->response(true, 'Kategori berhasil dihapus');
    }
}