async function renderCategories() {
    const app = document.getElementById('app');

    app.innerHTML = `
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Data Kategori</h2>
            <button onclick="showCategoryForm()"
                class="bg-blue-600 text-white px-4 py-2 rounded">
                + Tambah Kategori
            </button>
        </div>

        <div id="categoryForm"></div>

        <div class="bg-white p-4 rounded-lg shadow overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b">
                        <th class="p-3">ID</th>
                        <th class="p-3">Nama Kategori</th>
                        <th class="p-3">Deskripsi</th>
                    </tr>
                </thead>
                <tbody id="categoryTable">
                    <tr>
                        <td colspan="3" class="p-3">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    `;

    loadCategories();
}

async function loadCategories() {
    try {
        const response = await CategoryService.getAll();
        const categories = response.data;
        const table = document.getElementById('categoryTable');

        table.innerHTML = categories.map(category => `
            <tr class="border-b">
                <td class="p-3">${category.id}</td>
                <td class="p-3">${escapeHtml(category.nama)}</td>
                <td class="p-3">${escapeHtml(category.deskripsi || '-')}</td>
            </tr>
        `).join('');

        if (categories.length === 0) {
            table.innerHTML = `
                <tr><td colspan="3" class="p-3">Belum ada kategori.</td></tr>
            `;
        }
    } catch (error) {
        console.error(error);
        document.getElementById('categoryTable').innerHTML = `
            <tr><td colspan="3" class="p-3">Gagal memuat kategori.</td></tr>
        `;
    }
}

function showCategoryForm() {
    document.getElementById('categoryForm').innerHTML = `
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h3 class="text-lg font-bold mb-4">Tambah Kategori</h3>

            <form id="addCategoryForm" class="space-y-3">
                <input name="nama" placeholder="Nama Kategori" required
                    class="w-full border p-2 rounded">

                <textarea name="deskripsi" placeholder="Deskripsi Kategori"
                    class="w-full border p-2 rounded"></textarea>

                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded">
                    Simpan
                </button>
            </form>
        </div>
    `;

    document.getElementById('addCategoryForm').addEventListener('submit', async function(event) {
        event.preventDefault();

        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        try {
            await CategoryService.create(data);
            alert('Kategori berhasil ditambahkan!');
            document.getElementById('categoryForm').innerHTML = '';
            loadCategories();
        } catch (error) {
            alert('Gagal menambahkan kategori.');
            console.error(error);
        }
    });
}