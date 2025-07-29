<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $host = 'localhost';
    $db = 'fanta';
    $user = 'root';
    $pass = 'polivalent-2025';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (PDOException $e) {
        header("Location: register.php?error=Erreur de connexion à la base.");
        exit;
    }

    // Nettoyage des données
    $username = htmlspecialchars(trim($_POST['username']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    // Validation
    if (empty($username) || empty($email) || empty($password)) {
        header("Location: register.php?error=Champs obligatoires manquants.");
        exit;
    }

    // Vérifier si l'email existe déjà
    $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        header("Location: index.php?error=Email déjà utilisé.");
        exit;
    }

    // Hash du mot de passe
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
    $created_at = date("Y-m-d H:i:s");

    // Insertion
    try {
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (username, email, password, created_at) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $passwordHash, $created_at]);
        header("Location: index.php?success=Inscription réussie !");
    } catch (PDOException $e) {
        header("Location: index.php?error=Erreur lors de l'insertion.");
    }
}
?>
