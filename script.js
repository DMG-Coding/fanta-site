document.addEventListener('DOMContentLoaded', function () {
  // Animation GSAP avec ScrollTrigger
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
  tl2.from("#cocacola", { rotate: "-90deg", top: "110%", left: "-100%" }, "ca");
  tl2.from(".lemon2", { rotate: "90deg", left: "100%", top: "110%" }, "ca");
  tl2.from("#pepsi", { rotate: "90deg", top: "110%", left: "100%" }, "ca");

  tl2.to("#orange-cut", { width: "18%", left: "42%", top: "204%" }, "ca");
  tl2.to("#fanta", { width: "35%", top: "210%", left: "33%" }, "ca");

  // Gestion du menu burger et de la connexion
  const burgerIcon = document.getElementById('burger-icon');
  const nav = document.querySelector('.cntr-nav');
  const loginBtn = document.getElementById('loginBtn');
  const registerBtn = document.getElementById('registerBtn');
  const logoutBtn = document.getElementById('logoutBtn');
  const links = document.querySelector('.links');
  const cntrNav = document.querySelector('.cntr-nav');

  let menuOpen = false;

  const toggleLinksVisibility = () => {
    if (isAuthenticated) {
      links.classList.remove('hidden');
      loginBtn.classList.add('hidden');
      registerBtn.classList.add('hidden');
      logoutBtn.classList.remove('hidden');
    } else {
      links.classList.add('hidden');
      loginBtn.classList.remove('hidden');
      registerBtn.classList.remove('hidden');
      logoutBtn.classList.add('hidden');
    }
  };

  loginBtn.addEventListener('click', () => {
    if (!isAuthenticated) {
      alert(loginMessage);
      // simulate authentication just for demo
      // In real case, redirect or AJAX login happens
    }
  });

  registerBtn.addEventListener('click', () => {
    if (!isAuthenticated) {
      alert(registerMessage);
      // simulate registration
    }
  });

  logoutBtn.addEventListener('click', () => {
    // Tu peux ici faire une redirection vers logout.php
    window.location.href = 'logout.php';
  });

  burgerIcon.addEventListener('click', () => {
    if (!isAuthenticated) {
      loginBtn.classList.remove('hidden');
      registerBtn.classList.remove('hidden');
      logoutBtn.classList.add('hidden');
      links.classList.add('hidden');
    } else {
      links.classList.toggle('show');
    }

    menuOpen = !menuOpen;
  });

  toggleLinksVisibility();
});
