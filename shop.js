document.addEventListener('DOMContentLoaded', () => {
    const searchBar = document.getElementById('search-bar');
    const filterButton = document.getElementById('apply-filters');

    // Barre de recherche
    searchBar.addEventListener('input', function () {
        const query = searchBar.value.toLowerCase();
        const products = document.querySelectorAll('.product-card');
        
        products.forEach(product => {
            const name = product.querySelector('h3').textContent.toLowerCase();
            product.style.display = name.includes(query) ? 'block' : 'none';
        });
    });

    // Application des filtres (dynamique)
    filterButton.addEventListener('click', () => {
        const selectedFlavors = Array.from(document.querySelectorAll('input[name="flavor"]:checked'))
            .map(input => input.value.toLowerCase());
        const selectedSize = document.querySelector('input[name="size"]:checked')?.value;
        const priceOrder = document.getElementById('price-filter').value;

        const products = Array.from(document.querySelectorAll('.product-card'));

        // Filtrage dynamique
        products.forEach(product => {
            const flavor = product.querySelector('h3').textContent.toLowerCase();
            const isVisible = (!selectedFlavors.length || selectedFlavors.includes(flavor));
            product.style.display = isVisible ? 'block' : 'none';
        });

        // Tri par prix (simulé ici)
        if (priceOrder === 'asc') {
            products.sort((a, b) => parseFloat(a.querySelector('.price').textContent) - parseFloat(b.querySelector('.price').textContent));
        } else if (priceOrder === 'desc') {
            products.sort((a, b) => parseFloat(b.querySelector('.price').textContent) - parseFloat(a.querySelector('.price').textContent));
        }

        // Rafraîchissement des produits dans l'ordre
        const productList = document.getElementById('product-list');
        products.forEach(product => {
            productList.appendChild(product); // Réorganiser les produits
        });
    });
});