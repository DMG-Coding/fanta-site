// =============================================
// ATTENDRE QUE LE DOM SOIT CHARGÉ
// =============================================
document.addEventListener('DOMContentLoaded', function () {
  
  // =============================================
  // ANIMATIONS GSAP AVEC SCROLLTRIGGER
  // =============================================
  var tl = gsap.timeline({
    scrollTrigger: {
      trigger: ".two",
      start: "0% 95%",
      end: "70% 50%",
      scrub: true,
    },
  });

  tl.to("#fanta", { top: "120%", left: "0%" }, "orange");
  tl.to("#orange-cut", { top: "160%", left: "23%" }, "orange");
  tl.to("#orange", { width: "15%", top: "165%", right: "10%" }, "orange");
  tl.to("#leaf", { top: "100%", rotate: "130deg", left: "70%" }, "orange");
  tl.to("#leaf2", { top: "110%", rotate: "130deg", left: "0%" }, "orange");

  var tl2 = gsap.timeline({
    scrollTrigger: {
      trigger: ".three",
      start: "0% 95%",
      end: "20% 50%",
      scrub: true,
    },
  });

  tl2.from(".lemon1", { rotate: "-90deg", left: "-100%", top: "110%" }, "ca");
  tl2.from("#prime-bottle1", { rotate: "-90deg", top: "110%", left: "-100%" }, "ca");
  tl2.from(".lemon2", { rotate: "90deg", left: "100%", top: "110%" }, "ca");
  tl2.from("#prime-bottle2", { rotate: "90deg", top: "110%", left: "100%" }, "ca");
  tl2.to("#orange-cut", { width: "18%", left: "42%", top: "204%" }, "ca");
  tl2.to("#fanta", { width: "35%", top: "210%", left: "33%" }, "ca");

  // =============================================
  // GESTION DU MENU BURGER
  // =============================================
  const burgerIcon = document.getElementById('burger-icon');
  const cntrNav = document.querySelector('.cntr-nav');
  
  if (burgerIcon && cntrNav) {
    // Ouvrir/fermer le menu burger
    burgerIcon.addEventListener('click', function(e) {
      e.stopPropagation();
      cntrNav.classList.toggle('show');
    });

    // Fermer le menu quand on clique sur un lien de navigation
    const navLinks = cntrNav.querySelectorAll('a');
    navLinks.forEach(link => {
      link.addEventListener('click', function() {
        cntrNav.classList.remove('show');
      });
    });

    // Fermer le menu quand on clique en dehors
    document.addEventListener('click', function(event) {
      if (!burgerIcon.contains(event.target) && !cntrNav.contains(event.target)) {
        cntrNav.classList.remove('show');
      }
    });
  }

  // =============================================
  // GESTION DES FORMULAIRES D'AUTHENTIFICATION
  // =============================================
  const registerContainer = document.getElementById('register-container');
  const loginContainer = document.getElementById('login-container');
  
  // Boutons de fermeture
  const closeRegister = document.getElementById('closeRegister');
  const closeLogin = document.getElementById('closeLogin');

  // =============================================
  // UTILISATION DE LA DÉLÉGATION D'ÉVÉNEMENTS
  // Pour gérer les clics sur tous les boutons (desktop et mobile)
  // =============================================
  
  document.addEventListener('click', function(e) {
    // Gestion des boutons d'inscription (desktop et mobile)
    if (e.target.id === 'registerBtn' || e.target.id === 'registerBtnMobile') {
      e.preventDefault();
      registerContainer.style.display = 'flex';
      loginContainer.style.display = 'none';
      // Fermer le menu burger si on est sur mobile
      if (cntrNav) {
        cntrNav.classList.remove('show');
      }
    }
    
    // Gestion des boutons de connexion (desktop et mobile)
    if (e.target.id === 'loginBtn' || e.target.id === 'loginBtnMobile') {
      e.preventDefault();
      loginContainer.style.display = 'flex';
      registerContainer.style.display = 'none';
      // Fermer le menu burger si on est sur mobile
      if (cntrNav) {
        cntrNav.classList.remove('show');
      }
    }
    
    // Gestion des boutons de déconnexion (desktop et mobile)
    if (e.target.id === 'logoutBtn' || e.target.id === 'logoutBtnMobile') {
      e.preventDefault();
      if (confirm('Voulez-vous vraiment vous déconnecter ?')) {
        window.location.href = 'logout.php';
      }
    }
  });

  // =============================================
  // FERMETURE DES FORMULAIRES
  // =============================================
  if (closeRegister) {
    closeRegister.addEventListener('click', function() {
      registerContainer.style.display = 'none';
    });
  }
  
  if (closeLogin) {
    closeLogin.addEventListener('click', function() {
      loginContainer.style.display = 'none';
    });
  }

  // Fermer les formulaires en cliquant sur le fond
  if (registerContainer) {
    registerContainer.addEventListener('click', function(e) {
      if (e.target === registerContainer) {
        registerContainer.style.display = 'none';
      }
    });
  }
  
  if (loginContainer) {
    loginContainer.addEventListener('click', function(e) {
      if (e.target === loginContainer) {
        loginContainer.style.display = 'none';
      }
    });
  }

  // =============================================
  // AFFICHAGE/MASQUAGE DU MOT DE PASSE
  // =============================================
  const showBtn = document.getElementById('show');
  const hideBtn = document.getElementById('hide');
  const passwordField = document.getElementById('password');
  
  if (showBtn) {
    showBtn.addEventListener('click', function() {
      if (passwordField) {
        passwordField.type = 'text';
        showBtn.style.display = 'none';
        if (hideBtn) hideBtn.style.display = 'inline';
      }
    });
  }
  
  if (hideBtn) {
    hideBtn.addEventListener('click', function() {
      if (passwordField) {
        passwordField.type = 'password';
        hideBtn.style.display = 'none';
        if (showBtn) showBtn.style.display = 'inline';
      }
    });
  }

  // =============================================
  // GESTION DES LIENS DE CONNEXION SOCIALE
  // =============================================
  const socialLinks = document.querySelectorAll('.links');
  socialLinks.forEach(link => {
    link.addEventListener('click', function() {
      const url = this.getAttribute('data-url');
      if (url) {
        window.open(url, '_blank');
      }
    });
  });

  // =============================================
  // AFFICHAGE DES MESSAGES SUCCESS/ERROR
  // =============================================
  const params = new URLSearchParams(window.location.search);
  const messageElement = document.getElementById('message');
  
  if (messageElement) {
    if (params.has('success')) {
      messageElement.textContent = params.get('success');
      messageElement.style.color = 'green';
      messageElement.style.display = 'block';
    } else if (params.has('error')) {
      messageElement.textContent = params.get('error');
      messageElement.style.color = 'red';
      messageElement.style.display = 'block';
    }
  }

  // Masquer automatiquement les notifications après 5 secondes
  const notifications = document.querySelectorAll('.notification');
  notifications.forEach(notification => {
    setTimeout(() => {
      notification.style.opacity = '0';
      setTimeout(() => {
        notification.style.display = 'none';
      }, 300);
    }, 5000);
  });

  // =============================================
  // INITIALISATION DE L'INTERFACE
  // =============================================
  console.log('Script chargé avec succès');
  console.log('État de connexion:', isAuthenticated ? 'Connecté' : 'Non connecté');
});