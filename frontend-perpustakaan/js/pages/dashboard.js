async function renderDashboard() {
    const app = document.getElementById('app');

    app.innerHTML = `
        <h2 class="text-2xl font-bold mb-6">Dashboard</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-blue-600 text-white p-6 rounded-lg shadow">
                <h3 class="text-lg">Total Buku</h3>
                <p id="totalBooks" class="text-3xl font-bold">0</p>
            </div>

            <div class="bg-orange-500 text-white p-6 rounded-lg shadow">
                <h3 class="text-lg">Kategori</h3>
                <p id="totalCategories" class="text-3xl font-bold">0</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-xl font-bold mb-4">5 Buku Terbaru</h3>
            <div id="latestBooks">Memuat data...</div>
        </div>
    `;

    try {
        const books = await BookService.getAll();
        const categories = await CategoryService.getAll();

        document.getElementById('totalBooks').textContent = books.data.length;
        document.getElementById('totalCategories').textContent = categories.data.length;

        const latest = books.data.slice(-5).reverse();

        document.getElementById('latestBooks').innerHTML = latest.length
            ? latest.map(book => `
                <div class="border-b py-3">
                    <p class="font-semibold">${escapeHtml(book.judul)}</p>
                    <p class="text-gray-500 text-sm">${escapeHtml(book.penulis)}</p>
                </div>
            `).join('')
            : '<p>Belum ada data buku.</p>';

    } catch (error) {
        document.getElementById('latestBooks').textContent =
            'Gagal memuat data. Periksa koneksi API.';
        console.error(error);
    }
}