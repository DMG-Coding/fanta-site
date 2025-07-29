<?php
session_start();

$host = 'localhost';
$username = 'root';
$password = 'polivalent-2025';
$dbname = 'fanta';

$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

// Vérification d'authentification et rôle admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Récupération des données
$result_users = mysqli_query($conn, "SELECT * FROM utilisateurs");
$result_messages = mysqli_query($conn, "SELECT * FROM messages");
$result_admins = mysqli_query($conn, "SELECT id, username, email, created_at, role FROM utilisateurs WHERE role = 'admin'");
$available_orders_result = mysqli_query($conn, "SELECT nom, quantite_disponible FROM commandes_disponibles");
$placed_orders_result = mysqli_query($conn, "SELECT produit_id, nom, SUM(quantite_commande) AS total_commandes FROM commandes_placees GROUP BY produit_id, nom");

$order_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM commandes_disponibles"))['total'];
$message_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM messages"))['total'];

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        :root {
            --bg: #fff;
            --text: #333;
            --card: #f4f4f4;
            --header: #333;
            --header-text: #fff;
            --accent: #3498db;
            --menu-bg: rgba(0, 0, 0, 0.6);
        }

        body.dark {
            --bg: #1e1e1e;
            --text: #f0f0f0;
            --card: #2c2c2c;
            --header: #111;
            --header-text: #fff;
            --accent: #9b59b6;
            --menu-bg: rgba(255, 255, 255, 0.1);
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg);
            color: var(--text);
            transition: background 0.3s, color 0.3s;
        }

        header {
            text-align: center;
            margin-top: 80px;
            animation: rotateIn 2s ease;
        }

        .welcome {
            font-size: 60px;
            font-weight: bold;
            display: inline-block;
            animation: rotateIn 1.2s ease-in-out;
            color: var(--accent);
        }

        @keyframes rotateIn {
            0% {transform: rotate(-360deg) scale(0.5);opacity: 0;}
            60% {transform: rotate(20deg) scale(1.1);opacity: 1;}
            80% {transform: rotate(-10deg);}
            100% {transform: rotate(0deg) scale(1);}
        }

        /* Menu */
        .menu-btn {
            font-size: 30px;
            position: fixed;
            top: 20px;
            left: 20px;
            cursor: pointer;
            background: none;
            border: none;
            color: var(--text);
            z-index: 1000;
        }

        .theme-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            border: 2px solid var(--text);
            background: none;
            padding: 6px 10px;
            border-radius: 5px;
            cursor: pointer;
            z-index: 1000;
            color: var(--text);
        }

        .side-menu {
            position: fixed;
            top: 0;
            left: -290px;
            width: 290px;
            height: 100%;
            background: var(--menu-bg);
            backdrop-filter: blur(10px);
            transition: left 0.3s;
            padding-top: 70px;
            z-index: 999;
        }

        .side-menu.active {
            left: 0;
        }

        .side-menu a {
            display: block;
            padding: 15px;
            color: var(--header-text);
            text-decoration: none;
            border-bottom: 1px solid #777;
            font-weight: 600;
        }

        .side-menu a:hover {
            background-color: var(--accent);
        }

        .close-menu {
            position: absolute;
            top: 20px;
            right: 15px;
            font-size: 20px;
            cursor: pointer;
            color: var(--header-text);
        }

        /* Sections */
        .section {
            display: none;
            padding: 20px;
            margin-left: 270px;
            min-height: 80vh;
        }

        .section.active {
            display: block;
        }

        section h2 {
            color: var(--accent);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: var(--card);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background: var(--accent);
            color: #fff;
        }

        canvas {
            max-width: 100%;
            height: auto !important;
        }

        /* Styles pour le dashboard Fanta */
        .dashboard-container {
            background: #1a1a1a;
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }

        .dashboard-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .dashboard-title {
            background: linear-gradient(45deg, #ff6b35, #ff9500);
            color: white;
            padding: 15px 30px;
            border-radius: 25px;
            font-size: 24px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 20px;
        }

        .department-tabs {
            display: flex;
            justify-content: center;
            gap: 0;
            margin-bottom: 30px;
        }

        .dept-tab {
            background: #8B4A9F;
            color: white;
            padding: 12px 40px;
            border: none;
            font-weight: bold;
            cursor: pointer;
        }

        .dept-tab:first-child {
            border-radius: 10px 0 0 10px;
        }

        .dept-tab:last-child {
            border-radius: 0 10px 10px 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: #2a2a2a;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            border: 2px solid rgba(255, 255, 255, 0.1);
        }

        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #ff9500;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 14px;
            color: #ccc;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .chart-container {
            background: #2a2a2a;
            padding: 20px;
            border-radius: 10px;
            border: 2px solid rgba(255, 255, 255, 0.1);
        }

        .chart-title {
            color: white;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
        }

        .gender-chart {
            display: flex;
            height: 200px;
            border-radius: 10px;
            overflow: hidden;
        }

        .gender-male {
            background: #ff6b35;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            flex-direction: column;
        }

        .gender-female {
            background: #4a90e2;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            flex-direction: column;
        }

        .bottom-charts {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
        }

        .job-role-table {
            background: #2a2a2a;
            padding: 20px;
            border-radius: 10px;
            border: 2px solid rgba(255, 255, 255, 0.1);
        }

        .job-table {
            width: 100%;
            border-collapse: collapse;
        }

        .job-table th,
        .job-table td {
            padding: 8px 12px;
            text-align: left;
            border-bottom: 1px solid #444;
        }

        .job-table th {
            color: #ccc;
            font-size: 12px;
        }

        .job-table td {
            color: white;
        }

        .age-bars {
            display: flex;
            align-items: end;
            justify-content: space-around;
            height: 150px;
            margin-top: 20px;
        }

        .age-bar {
            width: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .bar {
            width: 100%;
            margin-bottom: 10px;
            border-radius: 5px 5px 0 0;
        }

        .bar-label {
            font-size: 12px;
            color: #ccc;
        }

        .salary-bars {
            margin-top: 20px;
        }

        .salary-bar {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .salary-label {
            width: 80px;
            font-size: 12px;
            color: #ccc;
        }

        .salary-bar-fill {
            height: 25px;
            border-radius: 3px;
            margin-right: 10px;
            display: flex;
            align-items: center;
            justify-content: end;
            padding-right: 10px;
            color: white;
            font-weight: bold;
        }

        .role-bars {
            margin-top: 20px;
        }

        .role-bar {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .role-label {
            width: 150px;
            font-size: 12px;
            color: #ccc;
        }

        .role-bar-fill {
            height: 25px;
            border-radius: 3px;
            margin-right: 10px;
            display: flex;
            align-items: center;
            justify-content: end;
            padding-right: 10px;
            color: white;
            font-weight: bold;
        }

        .line-chart {
            height: 150px;
            position: relative;
            margin-top: 20px;
        }

        .donut-chart {
            width: 200px;
            height: 200px;
            margin: 0 auto;
            position: relative;
        }
    </style>
</head>
<body>
    <button class="menu-btn" onclick="toggleMenu()">
     ☰ <span style="color: purple; font-size: 30px; font-weight: bold;">DASHBOARD</span>
    </button>

    <button class="theme-toggle" onclick="toggleTheme()">🌓 Thème</button>

    <div class="side-menu" id="sideMenu">
        <span class="close-menu" onclick="toggleMenu()">✖</span>
        <a href="#" onclick="showSection('accueil'); return false;">Accueil</a>
        <a href="#" onclick="showSection('utilisateurs'); return false;">Utilisateurs</a>
        <a href="#" onclick="showSection('admin'); return false;">Admins</a>
        <a href="#" onclick="showSection('messages'); return false;">Messages</a>
        <a href="#" onclick="showSection('disponibles'); return false;">Commandes Disponibles</a>
        <a href="#" onclick="showSection('placees'); return false;">Commandes Placées</a>
        <a href="?logout=true">Déconnexion</a>
    </div>

    <header>
        <h1 class="welcome">Bienvenue sur Fanta <?php echo htmlspecialchars($_SESSION['username']); ?> 👋</h1>
    </header>

    <div class="section active" id="accueil">
        <h2>Statistiques Générales</h2>
        <p><strong>Utilisateurs :</strong> <?= mysqli_num_rows($result_users); ?></p>
        <p><strong>Commandes Disponibles :</strong> <?= $order_count; ?></p>
        <p><strong>Messages :</strong> <?= $message_count; ?></p>
    </div>

    <div class="section" id="utilisateurs">
        <h2>Utilisateurs</h2>
        <table>
            <thead>
                <tr><th>ID</th><th>Nom</th><th>Email</th><th>Date</th><th>Rôle</th></tr>
            </thead>
            <tbody>
                <?php while ($user = mysqli_fetch_assoc($result_users)): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']); ?></td>
                        <td><?= htmlspecialchars($user['username']); ?></td>
                        <td><?= htmlspecialchars($user['email']); ?></td>
                        <td><?= htmlspecialchars($user['created_at']); ?></td>
                        <td><?= htmlspecialchars($user['role']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="section" id="admin">
        <h2>Utilisateurs Admin</h2>
        <table>
            <thead>
                <tr><th>ID</th><th>Nom d'utilisateur</th><th>Email</th><th>Date de création</th><th>Rôle</th></tr>
            </thead>
            <tbody>
                <?php while ($admin = mysqli_fetch_assoc($result_admins)): ?>
                    <tr>
                        <td><?= htmlspecialchars($admin['id']); ?></td>
                        <td><?= htmlspecialchars($admin['username']); ?></td>
                        <td><?= htmlspecialchars($admin['email']); ?></td>
                        <td><?= htmlspecialchars($admin['created_at']); ?></td>
                        <td><?= htmlspecialchars($admin['role']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="section" id="messages">
        <h2>Messages</h2>
        <table>
            <thead>
                <tr><th>ID</th><th>Nom</th><th>Email</th><th>Message</th><th>Date</th></tr>
            </thead>
            <tbody>
                <?php while ($msg = mysqli_fetch_assoc($result_messages)): ?>
                    <tr>
                        <td><?= htmlspecialchars($msg['id']); ?></td>
                        <td><?= htmlspecialchars($msg['nom']); ?></td>
                        <td><?= htmlspecialchars($msg['email']); ?></td>
                        <td><?= htmlspecialchars($msg['message']); ?></td>
                        <td><?= htmlspecialchars($msg['date_envoi']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="section" id="disponibles">
        <h2>Commandes Disponibles</h2>
        <?php
        // Stocker dans tableau PHP
        $available_orders = [];
        mysqli_data_seek($available_orders_result, 0); // Juste au cas où
        while ($row = mysqli_fetch_assoc($available_orders_result)) {
            $available_orders[] = $row;
        }
        ?>
        <canvas id="chartAvailable" width="400" height="200"></canvas>
    </div>

    <div class="section" id="placees">
        <div class="dashboard-container">
            <!-- Header -->
            <div class="dashboard-header">
                <div class="dashboard-title">Commandes Placées</div>
                <div class="department-tabs">
                    <button class="dept-tab">FANTA VOTRE BOISSON RAFRAICHISSANTE</button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">150</div>
                    <div class="stat-label">Fanta Orange</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">150</div>
                    <div class="stat-label">Fanta Raisin</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">200</div>
                    <div class="stat-label">Fanta Citron</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">210</div>
                    <div class="stat-label">Fanta Ananas</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">220</div>
                    <div class="stat-label">Fanta Orange</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">200</div>
                    <div class="stat-label">Fanta Kiwi et Raisin</div>
                </div>
            </div>

            <!-- Charts Grid -->
            <div class="charts-grid">
                <!-- Produits Disponibles (Donut Chart) -->
                <div class="chart-container">
                    <div class="chart-title">Produits Disponibles</div>
                    <canvas id="produitsDisponiblesChart" width="250" height="200"></canvas>
                </div>

                <!-- Produits les plus vendus du mois (Bar Chart) -->
                <div class="chart-container">
                    <div class="chart-title">Produits les plus vendus du mois</div>
                    <div class="age-bars">
                        <div class="age-bar">
                            <div class="bar" style="height: 80px; background: #4a90e2;"></div>
                            <div class="bar-label">Fanta Orange</div>
                            <div class="bar-label">116</div>
                        </div>
                        <div class="age-bar">
                            <div class="bar" style="height: 30px; background: #ff6b35;"></div>
                            <div class="bar-label">Fanta Raisin</div>
                            <div class="bar-label">44</div>
                        </div>
                        <div class="age-bar">
                            <div class="bar" style="height: 30px; background: #8B4A9F;"></div>
                            <div class="bar-label">Fanta Citron</div>
                            <div class="bar-label">43</div>
                        </div>
                        <div class="age-bar">
                            <div class="bar" style="height: 18px; background: #4CAF50;"></div>
                            <div class="bar-label">Fanta Ananas</div>
                            <div class="bar-label">26</div>
                        </div>
                        <div class="age-bar">
                            <div class="bar" style="height: 5px; background: #FFC107;"></div>
                            <div class="bar-label">Fanta Pomme</div>
                            <div class="bar-label">8</div>
                        </div>
                    </div>
                </div>

                <!-- Type de boisson -->
                <div class="chart-container">
                    <div class="chart-title">Type de boisson</div>
                    <div class="gender-chart">
                        <div class="gender-male">
                            <div style="font-size: 24px; margin-bottom: 10px;">140</div>
                            <div>Bouteille</div>
                        </div>
                        <div class="gender-female">
                            <div style="font-size: 24px; margin-bottom: 10px;">79</div>
                            <div>Canettes</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Charts -->
            <div class="bottom-charts">
                <!-- Produits le plus vendus de la semaine (Table) -->
                <div class="job-role-table">
                    <div class="chart-title">Produits le plus vendus de la semaine</div>
                    <table class="job-table">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>1</th>
                                <th>2</th>
                                <th>3</th>
                                <th>4</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>Fanta Orange</td><td>2</td><td>2</td><td>1</td><td>4</td><td>9</td></tr>
                            <tr><td>Fanta Raisin</td><td>5</td><td>2</td><td>3</td><td>2</td><td>12</td></tr>
                            <tr><td>Fanta Citron</td><td>20</td><td>8</td><td>21</td><td>13</td><td>62</td></tr>
                            <tr><td>Fanta Ananas</td><td>3</td><td>2</td><td>1</td><td>1</td><td>6</td></tr>
                            <tr><td>Fanta Pomme</td><td>2</td><td>2</td><td>4</td><td>2</td><td>10</td></tr>
                            <tr><td>Fanta Kiwi</td><td>0</td><td>1</td><td>1</td><td>0</td><td>2</td></tr>
                            <tr><td>Total</td><td>66</td><td>46</td><td>73</td><td>52</td><td>237</td></tr>
                        </tbody>
                    </table>
                </div>

                <!-- Saveurs les plus vendues -->
                <div class="chart-container">
                    <div class="chart-title">Saveurs les plus vendues</div>
                    <div class="salary-bars">
                        <div class="salary-bar">
                            <div class="salary-label">Fanta Orange</div>
                            <div class="salary-bar-fill" style="width: 80%; background: #8B4A9F;">163</div>
                        </div>
                        <div class="salary-bar">
                            <div class="salary-label">Fanta Raisin</div>
                            <div class="salary-bar-fill" style="width: 25%; background: #FFC107;">49</div>
                        </div>
                        <div class="salary-bar">
                            <div class="salary-label">Fanta Citron</div>
                            <div class="salary-bar-fill" style="width: 10%; background: #4a90e2;">20</div>
                        </div>
                        <div class="salary-bar">
                            <div class="salary-label">Fanta Pomme</div>
                            <div class="salary-bar-fill" style="width: 3%; background: #f44336;">5</div>
                        </div>
                    </div>
                </div>

                <!-- Diagramme des prix -->
                <div class="chart-container">
                    <div class="chart-title">Diagramme des prix</div>
                    <canvas id="priceChart" width="300" height="150"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Menu toggle
        function toggleMenu() {
            const menu = document.getElementById('sideMenu');
            menu.classList.toggle('active');
        }

        // Afficher la section active
        function showSection(id) {
            const sections = document.querySelectorAll('.section');
            sections.forEach(sec => sec.classList.remove('active'));
            document.getElementById(id).classList.add('active');
            // Fermer le menu si ouvert (pour mobile)
            document.getElementById('sideMenu').classList.remove('active');
            
            // Initialiser les graphiques si on affiche la section placees
            if (id === 'placees') {
                initializeFantaCharts();
            }
        }

        // Thème sombre / clair
        function toggleTheme() {
            document.body.classList.toggle('dark');
        }

        // Données commandes disponibles
        const availableLabels = [
            <?php
            $labels = [];
            foreach ($available_orders as $order) {
                $labels[] = '"' . addslashes($order['nom']) . '"';
            }
            echo implode(',', $labels);
            ?>
        ];
        const availableData = [
            <?php
            $data = [];
            foreach ($available_orders as $order) {
                $data[] = (int)$order['quantite_disponible'];
            }
            echo implode(',', $data);
            ?>
        ];

        // Données commandes placées
        <?php
        $placed_orders = [];
        mysqli_data_seek($placed_orders_result, 0);
        while ($row = mysqli_fetch_assoc($placed_orders_result)) {
            $placed_orders[] = $row;
        }
        ?>
        const placedLabels = [
            <?php
            $labelsPlaced = [];
            foreach ($placed_orders as $order) {
                $labelsPlaced[] = '"' . addslashes($order['nom']) . '"';
            }
            echo implode(',', $labelsPlaced);
            ?>
        ];
        const placedData = [
            <?php
            $dataPlaced = [];
            foreach ($placed_orders as $order) {
                $dataPlaced[] = (int)$order['total_commandes'];
            }
            echo implode(',', $dataPlaced);
            ?>
        ];

        // Graphique commandes disponibles
        const ctxAvailable = document.getElementById('chartAvailable').getContext('2d');
        const chartAvailable = new Chart(ctxAvailable, {
            type: 'bar',
            data: {
                labels: availableLabels,
                datasets: [{
                    label: 'Quantité Disponible',
                    data: availableData,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });

        // Fonction pour initialiser les graphiques Fanta
        function initializeFantaCharts() {
            // Graphique en donut pour produits disponibles
            const ctxProduits = document.getElementById('produitsDisponiblesChart').getContext('2d');
            new Chart(ctxProduits, {
                type: 'doughnut',
                data: {
                    labels: ['Fanta Orange', 'Fanta Raisin', 'Fanta Citron', 'Fanta Ananas', 'Fanta Pomme'],
                    datasets: [{
                        data: [38, 27, 15, 14, 5],
                        backgroundColor: ['#4a90e2', '#ff6b35', '#FFC107', '#4CAF50', '#8B4A9F'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: 'white',
                                fontSize: 10
                            }
                        }
                    }
                }
            });

            // Graphique linéaire pour l'évolution des prix
            const ctxPrice = document.getElementById('priceChart').getContext('2d');
            new Chart(ctxPrice, {
                type: 'line',
                data: {
                    labels: ['0-150', '150-200', '200-250'],
                    datasets: [{
                        label: 'Evolution des prix',
                        data: [59, 21, 18],
                        borderColor: '#4CAF50',
                        backgroundColor: 'rgba(76, 175, 80, 0.2)',
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#4CAF50',
                        pointBorderColor: '#4CAF50',
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: 'white'
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                color: 'white'
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            }
                        }
                    }
                }
            });
        }

        // Initialiser les graphiques au chargement de la page si la section est active
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('placees').classList.contains('active')) {
                setTimeout(initializeFantaCharts, 100);
            }
        });
    </script>
</body>
</html>