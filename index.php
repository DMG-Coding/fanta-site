<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="./Assets/favicon.ico">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>FANTA Votre boisson Rafraichissant</title>
</head>
<body>
    <!-- Messages de succès/erreur -->
    <?php if (isset($_GET['success'])): ?>
        <div class="notification success-notification">
            <?php echo htmlspecialchars($_GET['success']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="notification error-notification">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <!-- Affichage du nom de l'utilisateur en haut à droite (Desktop uniquement) -->
    <?php if (isset($_SESSION['username'])): ?>
    <div class="header">
        <?php echo htmlspecialchars($_SESSION['username']); ?>
    </div>
    <?php endif; ?>

    <div id="main">
        <nav>
            <div class="burger-menu">
                <i class="ri-menu-fill" id="burger-icon"></i>
            </div>

            <img src="Assets/Fanta-Logo.png" alt="Prime-Logo">
        
            <div class="cntr-nav">
                <!-- Les liens de navigation - affichés UNIQUEMENT si connecté -->
                <?php if (isset($_SESSION['username'])): ?>
                <div class="nav-links">
                    <a href="#">Home</a>
                    <a href="Products.html">Products</a>
                    <a href="shop.php">Shop</a>
                    <a href="contact.html">Contact</a>
                </div>
                <?php endif; ?>
                
                <!-- Section auth dans le menu burger (Mobile) -->
                <div class="mobile-auth-section">
                    <?php if (isset($_SESSION['username'])): ?>
                        <div class="mobile-user-info">
                            <span>👤 <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        </div>
                        <button id="logoutBtnMobile" class="auth-btn">Se Déconnecter</button>
                    <?php else: ?>
                        <button id="registerBtnMobile" class="auth-btn">S'Enregistrer</button>
                        <button id="loginBtnMobile" class="auth-btn">Se Connecter</button>
                    <?php endif; ?>
                </div>
            </div>
        
            <!-- Section auth (Desktop) - affichée UNIQUEMENT si connecté -->
            <?php if (isset($_SESSION['username'])): ?>
            <div class="auth-section desktop-auth">
                <button id="logoutBtn" class="auth-btn">Se Déconnecter</button>
            </div>
            <?php else: ?>
            <!-- Boutons auth pour utilisateur non connecté -->
            <div class="auth-section desktop-auth">
                <button id="registerBtn" class="auth-btn">S'Enregistrer</button>
                <button id="loginBtn" class="auth-btn">Se Connecter</button>
            </div>
            <?php endif; ?>
        
            <img id="leaf" src="Assets/leaf.webp" alt="Leaf"> 
        </nav>
        
        <div class="one">
            <h1>FANTA</h1>
            <img id="orange-cut" src="Assets/orange2.png" alt="Orange Cut"> 
            <img id="fanta" src="Assets/fanta.png" alt="Fanta"> 
            <img id="orange" src="Assets/orange.webp" alt="Orange" style="margin-top: 20px; margin-left: 80px;"> 
            <img id="leaf2" src="Assets/leaf2.png" alt="Leaf"> 
            <img id="leaf3" src="Assets/coconoutleaf.png" alt="Leaf"> 
        </div>

        <div class="two">
            <div class="lft-two">
                <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#228B22" d="M41.5,-59.5C49.8,-51.1,49.7,-33.6,50.7,-19.2C51.7,-4.7,53.8,6.7,52.4,18.9C51.1,31.1,46.3,44.1,36.9,52.9C27.6,61.8,13.8,66.5,-2.5,70C-18.8,73.4,-37.7,75.6,-52.5,68.5C-67.3,61.5,-78.2,45.2,-84.5,27.1C-90.9,9,-92.7,-10.8,-80.5,-19.3C-68.3,-27.8,-42.1,-24.8,-26.3,-30.8C-10.6,-36.8,-5.3,-51.7,5.7,-59.5C16.6,-67.3,33.2,-68,41.5,-59.5Z" transform="translate(100 100)" />
                </svg>
            </div>
            <div class="rght-two">
                <h1>Saveurs mises à jour</h1>
                <p>Voici la gamme passionnante de saveurs de Prime : une fusion palpitante de fruits vibrants qui ravira vos papilles gustatives ! Nos nouvelles boissons Prime regorgent d'une symphonie de saveurs rafraîchissantes : framboise bleue, punch tropical, citron vert, etc.</p>
            </div>
        </div>

        <div class="three">
            <div class="card">
                <img class="lemon lemon1" src="Assets/fanta-raisin.png" alt="">
                <img id="prime-bottle1" src="Assets/prime-blue.png" alt="">
                <h1>Blue Raspberry</h1>
                <button>l'icône pop qui fait vibrer tes papilles. Un goût électrique, un style unique — impossible à oublier.</button>
            </div>
            <div class="card">
                <h1>Tropical Punch</h1>
                <button>l'évasion en une gorgée. Un cocktail de saveurs exotiques qui réchauffe l'âme et rafraîchit le corps.</button>
            </div>
            <div class="card">
                <img class="lemon lemon2" src="Assets/fanta-pomme.png" alt="" style="margin-top: 100px;">
                <img id="prime-bottle2" src="Assets/prime-green.png" alt="">
                <h1>Lemon Lime</h1>
                <button>la fraîcheur qui claque. Un duo acidulé, pétillant et ultra désaltérant — à chaque gorgée, ça réveille !</button>
            </div>
        </div>

        <footer>
            <div class="footer-links">
                <a href="#home">Home</a>
                <a href="Products.html">Products</a>
                <a href="Shop.php">Shop</a>
                <a href="contact.html">Contact</a>
            </div>
            <p>&copy; 2024 Prime. All rights reserved. | Created by DMG Coding</p>
        </footer>
    </div>
    
    <!-- Formulaire d'enregistrement -->
    <div id="register-container" class="login-form">
        <div class="main">
            <div class="container">
                <button class="btn-retour" id="closeRegister">Retour</button>
                <h2>Créer un compte</h2>
                <form action="register.php" method="POST">
                    <input type="text" name="username" placeholder="Nom d'utilisateur" id="username" required>
                    <input type="email" name="email" placeholder="Email" id="email" required>
                    <div class="email-password-div">
                        <input type="password" name="password" placeholder="Mot de passe" id="password" required>
                        <div class="strong-div">
                            <strong id="show">SHOW</strong>
                            <strong id="hide" style="display:none;">HIDE</strong>
                        </div>
                    </div>
                    <button type="submit" name="submit">Sign up</button>
                </form>
                <p id="message"></p>
            </div>
        </div>
    </div>

    <!-- Formulaire de connexion -->
    <div id="login-container" class="login-form">
        <div class="main">
            <div class="container">
                <button class="btn-retour" id="closeLogin">Retour</button>
                <h2>Connexion</h2>
                <form action="login.php" method="POST" id="login-form">
                    <input type="text" placeholder="Nom d'utilisateur" id="login-username" name="username" required>
                    <input type="email" placeholder="Email" id="login-email" name="email" required>
                    <button type="submit" name="submit">Se connecter</button>
        
                    <!-- Connexions sociales -->
                    <div class="links-div">
                        <div class="links" data-url="https://accounts.google.com/signin">
                            <img src="https://cdn.pixabay.com/photo/2015/12/11/11/43/google-1088004_1280.png" width="30px">
                            <p>Continuer avec Google</p>
                        </div>
                        <div class="links" data-url="https://www.facebook.com/login">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/Facebook_f_logo_%282019%29.svg" width="30px">
                            <p>Continuer avec Facebook</p>
                        </div>
                        <div class="links" data-url="https://login.microsoftonline.com/">
                            <img src="https://cdn.pixabay.com/photo/2014/01/02/23/30/microsoft-237843_1280.png" width="30px">
                            <p>Continuer avec Microsoft</p>
                        </div>
                        <div class="links" data-url="https://appleid.apple.com/">
                            <img src="https://cdn.pixabay.com/photo/2018/05/08/21/08/apple-3383931_1280.png" width="30px">
                            <p>Continuer avec Apple</p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Variable JavaScript pour l'état de connexion -->
    <script>
        const isAuthenticated = <?= isset($_SESSION['username']) ? 'true' : 'false' ?>;
    </script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js" integrity="sha512-16esztaSRplJROstbIIdwX3N97V1+pZvV33ABoG1H2OyTttBxEGkTsoIVsiP1iaTtM8b3+hu2kB6pQ4Clr5yug==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js" integrity="sha512-Ic9xkERjyZ1xgJ5svx3y0u3xrvfT/uPkV99LBwe68xjy/mGtO+4eURHZBW2xW4SZbFrF1Tf090XqB+EVgXnVjw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="script.js"></script>
</body>
</html>