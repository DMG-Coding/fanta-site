<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'fanta';
$username = 'root'; // à adapter selon ton installation
$password = 'polivalent-2025'; // ou 'root' selon ton serveur local

try {
    // Connexion PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Activer les erreurs PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifier si la méthode est POST et que le reCAPTCHA est présent
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['g-recaptcha-response'])) {
        // Vérification du reCAPTCHA
        $recaptchaSecret = '6LeyZwsrAAAAACtktYEmXhB8SotGV8FY21aUCpVc'; // Remplacer par votre clé secrète
        $recaptchaResponse = $_POST['g-recaptcha-response'];
        $recaptchaUrl = "https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecret&response=$recaptchaResponse";

        // Vérification reCAPTCHA
        $recaptchaVerify = file_get_contents($recaptchaUrl);
        $recaptchaResult = json_decode($recaptchaVerify);

        // Si le reCAPTCHA échoue
        if (!$recaptchaResult->success) {
            die("Échec de la vérification reCAPTCHA. Veuillez réessayer.");
        }

        // Nettoyage des données pour éviter les attaques XSS et SQL
        $name = htmlspecialchars(strip_tags(trim($_POST["name"])));
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $message = htmlspecialchars(strip_tags(trim($_POST["message"]))); 

        // Validation des champs
        if (empty($name) || empty($email) || empty($message)) {
            die("Tous les champs doivent être remplis.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("L'adresse email est invalide.");
        }

        // Requête SQL d'insertion sécurisée
        $sql = "INSERT INTO messages (name, email, message) VALUES (:name, :email, :message)";
        $stmt = $pdo->prepare($sql);

        // Lier les paramètres
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':message', $message, PDO::PARAM_STR);

        // Exécuter la requête
        $stmt->execute();

        // Message de succès
        echo "Message envoyé avec succès !";
    } else {
        die("Erreur : reCAPTCHA manquant ou méthode incorrecte.");
    }
} catch (PDOException $e) {
    // En cas d'erreur PDO
    echo "Erreur : " . $e->getMessage();
}
?>
