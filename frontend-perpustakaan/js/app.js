function router() {
    const page = window.location.hash || '#dashboard';
    const app = document.getElementById('app');

    if (page === '#dashboard') {
        renderDashboard();
    } 
    else if (page === '#books') {
        renderBooks();
    } 
    else if (page === '#categories') {
        renderCategories();
    } 
    else {
        app.innerHTML = `
            <h2 class="text-xl font-bold">
                Halaman tidak ditemukan
            </h2>
        `;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    router();
});

window.addEventListener('hashchange', router);