// =============================================
// SCRIPT UNIVERSEL POUR LE MENU BURGER
// À ajouter dans toutes les pages
// =============================================

document.addEventListener('DOMContentLoaded', function () {
  
  // =============================================
  // GESTION DU MENU BURGER
  // =============================================
  const burgerIcon = document.getElementById('burger-icon');
  const cntrNav = document.querySelector('.cntr-nav');
  
  if (burgerIcon && cntrNav) {
    // Toggle menu au clic sur le burger
    burgerIcon.addEventListener('click', function(e) {
      e.stopPropagation();
      cntrNav.classList.toggle('show');
      console.log('Menu burger cliqué, classe show:', cntrNav.classList.contains('show'));
    });

    // Fermer le menu quand on clique sur un lien
    const navLinks = cntrNav.querySelectorAll('a');
    navLinks.forEach(link => {
      link.addEventListener('click', function() {
        cntrNav.classList.remove('show');
      });
    });

    // Fermer le menu quand on clique en dehors
    document.addEventListener('click', function(event) {
      const isClickInsideMenu = cntrNav.contains(event.target);
      const isClickOnBurger = burgerIcon.contains(event.target);
      
      if (!isClickInsideMenu && !isClickOnBurger && cntrNav.classList.contains('show')) {
        cntrNav.classList.remove('show');
      }
    });

    // Empêcher la propagation du clic sur le menu
    cntrNav.addEventListener('click', function(e) {
      e.stopPropagation();
    });
  } else {
    console.error('Burger icon ou cntr-nav non trouvé');
  }

  // =============================================
  // GESTION DU PANIER (pour shop.php)
  // =============================================
  const cartIcon = document.getElementById('open-cart');
  const cartSidebar = document.getElementById('cart-sidebar');
  const closeCartBtn = document.getElementById('close-cart');

  if (cartIcon && cartSidebar) {
    cartIcon.addEventListener('click', function(e) {
      e.preventDefault();
      cartSidebar.classList.add('open');
    });
  }

  if (closeCartBtn && cartSidebar) {
    closeCartBtn.addEventListener('click', function() {
      cartSidebar.classList.remove('open');
    });
  }

  // Fermer le panier en cliquant en dehors
  if (cartSidebar) {
    document.addEventListener('click', function(event) {
      const isClickInsideCart = cartSidebar.contains(event.target);
      const isClickOnCartIcon = cartIcon && cartIcon.contains(event.target);
      
      if (!isClickInsideCart && !isClickOnCartIcon && cartSidebar.classList.contains('open')) {
        cartSidebar.classList.remove('open');
      }
    });

    cartSidebar.addEventListener('click', function(e) {
      e.stopPropagation();
    });
  }
});

// =============================================
// FONCTIONS UTILITAIRES
// =============================================
function toggleMenu() {
  const cntrNav = document.querySelector('.cntr-nav');
  if (cntrNav) {
    cntrNav.classList.toggle('show');
  }
}

// Pour debug - à retirer en production
console.log('Script menu burger chargé');