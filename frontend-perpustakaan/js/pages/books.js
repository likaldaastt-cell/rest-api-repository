async function renderBooks() {
    const app = document.getElementById('app');

    app.innerHTML = `
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Data Buku</h2>
            <button onclick="showBookForm()"
                class="bg-blue-600 text-white px-4 py-2 rounded">
                + Tambah Buku
            </button>
        </div>

        <div id="bookForm"></div>

        <div class="bg-white p-4 rounded-lg shadow overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b">
                        <th class="p-3">ID</th>
                        <th class="p-3">Judul</th>
                        <th class="p-3">Penulis</th>
                        <th class="p-3">Tahun</th>
                        <th class="p-3">Stok</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody id="bookTable">
                    <tr><td colspan="6" class="p-3">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
    `;

    loadBooks();
}

async function loadBooks() {
    try {
        const response = await BookService.getAll();
        const books = response.data;
        const table = document.getElementById('bookTable');

        table.innerHTML = books.map(book => `
            <tr class="border-b">
                <td class="p-3">${book.id}</td>
                <td class="p-3">${escapeHtml(book.judul)}</td>
                <td class="p-3">${escapeHtml(book.penulis)}</td>
                <td class="p-3">${book.tahun}</td>
                <td class="p-3">${book.stok}</td>
                <td class="p-3">-</td>
            </tr>
        `).join('');

        if (books.length === 0) {
            table.innerHTML = '<tr><td colspan="6" class="p-3">Belum ada data buku.</td></tr>';
        }
    } catch (error) {
        console.error(error);
        document.getElementById('bookTable').innerHTML =
            '<tr><td colspan="6" class="p-3">Gagal memuat data buku.</td></tr>';
    }
}

function showBookForm() {
    document.getElementById('bookForm').innerHTML = `
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h3 class="text-lg font-bold mb-4">Tambah Buku</h3>

            <form id="addBookForm" class="space-y-3">
                <input name="judul" placeholder="Judul Buku" required
                    class="w-full border p-2 rounded">

                <input name="penulis" placeholder="Penulis" required
                    class="w-full border p-2 rounded">

                <input name="penerbit" placeholder="Penerbit" required
                    class="w-full border p-2 rounded">

                <input name="tahun" type="number" placeholder="Tahun Terbit" required
                    class="w-full border p-2 rounded">

                <input name="isbn" placeholder="ISBN" required
                    class="w-full border p-2 rounded">

                <input name="stok" type="number" placeholder="Stok" required
                    class="w-full border p-2 rounded">

                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded">
                    Simpan
                </button>
            </form>
        </div>
    `;

    document.getElementById('addBookForm').addEventListener('submit', async function(event) {
        event.preventDefault();

        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        data.tahun = Number(data.tahun);
        data.stok = Number(data.stok);

        try {
            await BookService.create(data);
            alert('Buku berhasil ditambahkan!');
            document.getElementById('bookForm').innerHTML = '';
            loadBooks();
        } catch (error) {
            alert('Gagal menambahkan buku. Periksa data dan API.');
            console.error(error);
        }
    });
}