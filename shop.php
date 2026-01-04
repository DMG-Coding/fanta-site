<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - Fanta</title>
    <!-- AJOUT DE REMIX ICON -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="styles44.css">
</head>
<body>
    <header>
        <nav>
            <div class="burger-menu">
                <i class="ri-menu-fill" id="burger-icon"></i>
            </div>
        
            <div class="cntr-nav links hidden">
                <a href="index.php">Home</a>
                <a href="Products.html">Products</a>
                <a href="shop.php">Shop</a>
                <a href="contact.html">Contact</a>
                <a href="#" class="cart-icon" id="open-cart" title="Voir le panier">
                   <i class="fas fa-shopping-cart" style="color: black; font-size: 20px;"></i>
                   <span id="cart-count">0</span>
                </a>
            </div>
        </nav>
        <h1>Fanta Shop</h1>
        <nav>
            <input type="text" id="search-bar" placeholder="Rechercher un produit...">
        </nav>
    </header>
    <br>
    <br>
    <br>
    <br>
    <br>
    <section id="filters">
        <h2>Filtres</h2>
        <form id="filter-form">
            <!-- Saveurs -->
            <fieldset>
                <legend>Saveurs</legend>
                <label><input type="checkbox" name="flavor" value="Orange"> Orange</label>
                <label><input type="checkbox" name="flavor" value="Citron"> Citron</label>
                <label><input type="checkbox" name="flavor" value="Kiwi et Raisin"> Kiwi et Raisin</label>
                <label><input type="checkbox" name="flavor" value="Raisin"> Raisin</label>
                <label><input type="checkbox" name="flavor" value="Ananas"> Ananas</label>
                <label><input type="checkbox" name="flavor" value="Pomme"> Pomme</label>
            </fieldset>

            <!-- Taille -->
            <fieldset>
                <legend>Type</legend>
                <label><input type="radio" name="size" value="Mini"> Bouteille</label>
                <label><input type="radio" name="size" value="Regular"> Canette</label>
            </fieldset>

            <!-- Prix -->
            <fieldset>
                <legend>Prix</legend>
                <select id="price-filter">
                    <option value="asc">Du moins cher au plus cher</option>
                    <option value="desc">Du plus cher au moins cher</option>
                </select>
            </fieldset>

            <button type="button" id="apply-filters">Appliquer</button>
        </form>
    </section>

    <section id="special-offers">
        <h2>Offres Spéciales</h2>
        <div class="promo-card">
            <h3>3 pour le prix de 2</h3>
            <p>Ajoutez 3 bouteilles et payez seulement 2 !</p>
        </div>
        <div class="promo-card">
            <h3>Pack Mix</h3>
            <p>Créez votre propre pack de saveurs dès maintenant !</p>
        </div>
    </section>

    <section id="product-list">
        <h2>Produits</h2>
        <div class="product-card">
            <img src="produit/fanta_PNG46.png" alt="Fanta Orange">
            <h3>Fanta Orange</h3>
            <p class="price">150 gdes</p>
            <button class="add-to-cart" data-id="1" data-name="Fanta Orange" data-price="150" data-image="produit/fanta_PNG46.png">Ajouter au panier</button>
        </div>
        <div class="product-card">
            <img src="produit/fanta_PNG47.png" alt="Fanta Raisin">
            <h3>Fanta Raisin</h3>
            <p class="price">150 gdes</p>
            <button class="add-to-cart" data-id="2" data-name="Fanta Raisin" data-price="150" data-image="produit/fanta_PNG47.png">Ajouter au panier</button>
        </div>
        <div class="product-card">
            <img src="produit/fanta_PNG48.png" alt="Fanta Raisin">
            <h3>Fanta Citron</h3>
            <p class="price">150 gdes</p>
            <button class="add-to-cart" data-id="3" data-name="Fanta Citron" data-price="150" data-image="produit/fanta_PNG48.png">Ajouter au panier</button>
        </div>
        <div class="product-card">
            <img src="produit/fanta_PNG49.png" alt="Fanta Raisin">
            <h3>Fanta Pomme</h3>
            <p class="price">150 gdes</p>
            <button class="add-to-cart" data-id="4" data-name="Fanta Pomme" data-price="150" data-image="produit/fanta_PNG49.png">Ajouter au panier</button>
        </div>
        <div class="product-card">
            <img src="produit/Canettes-Fanta-Raisin.png" alt="Fanta Raisin">
            <h3>Fanta Raisin</h3>
            <p class="price">200 gdes</p>
            <button class="add-to-cart" data-id="5" data-name="Fanta Raisin" data-price="200" data-image="produit/Canettes-Fanta-Raisin.png">Ajouter au panier</button>
        </div>
        <div class="product-card">
            <img src="produit/fanta_PNG25.png" alt="Fanta Raisin">
            <h3>Fanta Citron</h3>
            <p class="price">200 gdes</p>
            <button class="add-to-cart" data-id="6" data-name="Fanta Citron" data-price="200" data-image="produit/fanta_PNG25.png">Ajouter au panier</button>
        </div>
        <div class="product-card">
            <img src="produit/fanta_PNG26.png" alt="Fanta Raisin">
            <h3>Fanta Ananas</h3>
            <p class="price">210 gdes</p>
            <button class="add-to-cart" data-id="7" data-name="Fanta Ananas" data-price="210" data-image="produit/fanta_PNG26.png">Ajouter au panier</button>
        </div>
        <div class="product-card">
            <img src="produit/fanta_PNG27.png" alt="Fanta Raisin">
            <h3>Fanta Orange</h3>
            <p class="price">220 gdes</p>
            <button class="add-to-cart" data-id="8" data-name="Fanta Orange" data-price="220" data-image="produit/fanta_PNG27.png">Ajouter au panier</button>
        </div>
        <div class="product-card">
            <img src="produit/fantaexcoticcan_150x@2x.avif" alt="Fanta Raisin">
            <h3>Fanta Kiwi et Raisin</h3>
            <p class="price">200 gdes</p>
            <button class="add-to-cart" data-id="9" data-name="Fanta Kiwi et Raisin" data-price="200" data-image="produit/fantaexcoticcan_150x@2x.avif">Ajouter au panier</button>
        </div>
        <div class="product-card">
            <img src="produit/fanta_PNG28.png" alt="Fanta Raisin">
            <h3>Fanta Pomme </h3>
            <p class="price">250 gdes</p>
            <button class="add-to-cart" data-id="10" data-name="Fanta Pomme" data-price="250" data-image="produit/fanta_PNG28.png">Ajouter au panier</button>
        </div>
        <div class="product-card">
            <img src="produit/fanta_PNG31.png" alt="Fanta Raisin">
            <h3>Fanta Pomme </h3>
            <p class="price">240 gdes</p>
            <button class="add-to-cart" data-id="11" data-name="Fanta Pomme" data-price="240" data-image="produit/fanta_PNG31.png">Ajouter au panier</button>
        </div>
        <div class="product-card">
            <img src="produit/fanta_PNG29.png" alt="Fanta Raisin">
            <h3>Fanta Pomme </h3>
            <p class="price">250 gdes</p>
            <button class="add-to-cart" data-id="12" data-name="Fanta Pomme" data-price="250" data-image="produit/fanta_PNG29.png">Ajouter au panier</button>
        </div>
        <div class="product-card">
            <img src="produit/fanta_PNG30.png" alt="Fanta Raisin">
            <h3>Fanta Pomme </h3>
            <p class="price">250 gdes</p>
            <button class="add-to-cart" data-id="13" data-name="Fanta Pomme" data-price="250" data-image="produit/fanta_PNG30.png">Ajouter au panier</button>
        </div>
    </section>

    <!-- Slide Panier -->
    <div id="cart-sidebar" class="cart-sidebar">
        <div class="cart-header">
            <h3>Mon Panier</h3>
            <button id="close-cart">X</button>
        </div>
        <div id="cart-items"></div>
        <div class="cart-footer">
            <button id="clear-cart">Vider le panier</button>
            <button id="checkout" type="button">Commander</button>

            <!-- Formulaire MonCash caché au début -->
            <form id="moncash-form" action="moncashPay/exec.php" method="POST" style="display:none;">
                <input type="hidden" id="amount-input" name="amount" value="">
              <input type="image" 
              src="https://sandbox.moncashbutton.digicelgroup.com/Moncash-middleware/resources/assets/images/MC_button_kr.png" 
              style="width:100px;">
            </form>
        </div>
    </div>
    <script src="shopfanta11.js"></script>
</body>
</html>