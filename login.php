<?php
session_start();

// Connexion à la base de données
$host = 'localhost';
$dbname = 'fanta';
$db_user = 'root';
$db_pass = 'polivalent-2025';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $db_user, $db_pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        $username = isset($_POST['username']) ? htmlspecialchars(trim($_POST['username'])) : '';
        $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL) : '';

        if (empty($username) || empty($email)) {
            echo "Veuillez remplir tous les champs.";
            exit;
        }

        // Recherche de l'utilisateur dans la base de données
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE username = ? AND email = ?");
        $stmt->execute([$username, $email]);
        $user = $stmt->fetch();

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            header('Location: index.php');  // Rediriger vers la page d'accueil après une connexion réussie
            exit;
        } else {
            echo "Identifiants invalides";
        }

    } catch (PDOException $e) {
        echo "Erreur de connexion.";
    }
}
?>

