#!/bin/bash

BASE_URL="http://localhost:8000/api/books"

echo "================================"
echo " PENGUJIAN OTOMATIS REST API"
echo "================================"

echo ""
echo "TEST 1: GET Semua Buku"
curl -s "$BASE_URL" | jq

echo ""
echo "TEST 2: POST Tambah Buku"

RESPONSE=$(curl -s -X POST "$BASE_URL" \
-H "Content-Type: application/json" \
-d '{"judul":"Buku Uji Otomatis","penulis":"Penulis Uji","tahun":2024,"stok":5}')

echo "$RESPONSE" | jq

ID=$(echo "$RESPONSE" | jq -r '.data.id')

echo "ID buku baru: $ID"

if [ "$ID" = "null" ] || [ -z "$ID" ]; then
    echo "GAGAL: Buku tidak berhasil ditambahkan."
    exit 1
fi

echo ""
echo "TEST 3: GET Detail Buku"
curl -s "$BASE_URL/$ID" | jq

echo ""
echo "TEST 4: PUT Update Buku"
curl -s -X PUT "$BASE_URL/$ID" \
-H "Content-Type: application/json" \
-d '{"judul":"Buku Uji Otomatis Revisi","penulis":"Penulis Uji","tahun":2025,"stok":10}' | jq

echo ""
echo "TEST 5: DELETE Buku"
curl -s -X DELETE "$BASE_URL/$ID" | jq

echo ""
echo "TEST 6: Validasi Data"
curl -s -X POST "$BASE_URL" \
-H "Content-Type: application/json" \
-d '{"judul":"","penulis":"","tahun":0,"stok":0}' | jq

echo ""
echo "================================"
echo " PENGUJIAN SELESAI"
echo "================================"