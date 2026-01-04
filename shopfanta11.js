let cart = []; // Déclaration de la variable cart avant l'événement DOMContentLoaded

document.addEventListener('DOMContentLoaded', () => {
    // =============================================
    // GESTION DU MENU BURGER
    // =============================================
    const burgerIcon = document.getElementById('burger-icon');
    const cntrNav = document.querySelector('.cntr-nav');
    
    if (burgerIcon && cntrNav) {
        burgerIcon.addEventListener('click', function(e) {
            e.stopPropagation();
            cntrNav.classList.toggle('show');
        });

        const navLinks = cntrNav.querySelectorAll('a');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                cntrNav.classList.remove('show');
            });
        });

        document.addEventListener('click', function(event) {
            if (!cntrNav.contains(event.target) && !burgerIcon.contains(event.target)) {
                cntrNav.classList.remove('show');
            }
        });

        cntrNav.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    // =============================================
    // CODE ORIGINAL DU SHOP
    // =============================================
    const searchBar = document.getElementById('search-bar');
    const filterButton = document.getElementById('apply-filters');
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    const cartSidebar = document.getElementById('cart-sidebar');
    const openCartButton = document.getElementById('open-cart');
    const closeCartButton = document.getElementById('close-cart');
    const cartCount = document.getElementById('cart-count');
    const cartItems = document.getElementById('cart-items');
    const clearCartButton = document.getElementById('clear-cart');
    const checkoutButton = document.getElementById('checkout');
    const moncashForm = document.getElementById('moncash-form');
    const amountInput = document.getElementById('amount-input');

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
    
    // Fonction pour mettre à jour le panier
    function updateCart() {
        cartItems.innerHTML = '';
        let totalItems = 0;
    
        cart.forEach(item => {
            const itemDiv = document.createElement('div');
            itemDiv.classList.add('cart-item');
            
            itemDiv.innerHTML = `
                <img src="${item.image}" alt="${item.name}">
                <span>${item.name} - ${item.price} gdes</span>
                <div class="quantity">
                    <button class="decrease">-</button>
                    <span>${item.quantity}</span>
                    <button class="increase">+</button>
                </div>
            `;
            
            // Ajouter les boutons + et -
            itemDiv.querySelector('.increase').addEventListener('click', () => {
                item.quantity++;
                updateCart();
            });
            itemDiv.querySelector('.decrease').addEventListener('click', () => {
                if (item.quantity > 1) {
                    item.quantity--;
                    updateCart();
                }
            });
            
            cartItems.appendChild(itemDiv);
            totalItems += item.quantity;
        });
    
        // Mise à jour du compteur de produits
        cartCount.textContent = totalItems;
    }

    // Ajouter un produit au panier
    addToCartButtons.forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const price = parseFloat(button.getAttribute('data-price'));
            const image = button.getAttribute('data-image');
    
            // Vérifier si l'article est déjà dans le panier
            const existingItem = cart.find(item => item.id === id);
            if (existingItem) {
                existingItem.quantity++;
            } else {
                cart.push({ id, name, price, image, quantity: 1 });
            }
    
            updateCart();
        });
    });

    // Ouvrir le panier
    openCartButton.addEventListener('click', (e) => {
        e.preventDefault(); // empêche le comportement par défaut du lien
        cartSidebar.classList.add('open');
    });

    // Fermer le panier
    closeCartButton.addEventListener('click', () => {
        cartSidebar.classList.remove('open');
    });

    // Fermer le panier en cliquant en dehors
    document.addEventListener('click', function(event) {
        const isClickInsideCart = cartSidebar.contains(event.target);
        const isClickOnCartIcon = openCartButton && openCartButton.contains(event.target);
        
        if (!isClickInsideCart && !isClickOnCartIcon && cartSidebar.classList.contains('open')) {
            cartSidebar.classList.remove('open');
        }
    });

    cartSidebar.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // Vider le panier
    clearCartButton.addEventListener('click', () => {
        cart = [];
        updateCart();
    });

    // Quand on clique sur Commander
    checkoutButton.addEventListener('click', (e) => {
        e.preventDefault(); // empêche tout comportement par défaut
        if (cart.length === 0) {
            alert('Votre panier est vide.');
            return;
        }
        const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
        amountInput.value = total.toFixed(2);
        moncashForm.style.display = 'block'; // affiche le formulaire
    });

});