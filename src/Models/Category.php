<?php

namespace App\Models;

class Category
{
    private $id;
    private $nama;
    private $deskripsi;

    public function __construct($nama, $deskripsi = '')
    {
        $this->nama = $nama;
        $this->deskripsi = $deskripsi;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNama()
    {
        return $this->nama;
    }

    public function getDeskripsi()
    {
        return $this->deskripsi;
    }
}