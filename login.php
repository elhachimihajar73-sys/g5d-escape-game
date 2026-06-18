<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/database.php';

$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM G5D_users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch();

    if($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: accueil.php');
        exit();
    } else {
        $error = "Identifiants incorrects";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Escape Game G5D</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">
<div class="auth-card">
    <div class="auth-header">
        <div class="auth-icon">⚡</div>
        <h1>Connexion</h1>
        <p>Accédez à la salle Électricité</p>
    </div>

    <?php if($error): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="input-group">
            <label>Nom d'utilisateur ou Email</label>
            <input type="text" name="username" required placeholder="ex: ingenieur123">
        </div>

        <div class="input-group">
            <label>Mot de passe</label>
            <input type="password" name="password" required placeholder="••••••••">
        </div>

        <button type="submit" class="auth-btn">Se connecter →</button>
    </form>

    <div class="auth-footer">
        <p>Pas encore de compte ? <a href="register.php">S'inscrire</a></p>
        <p style="margin-top: 10px;"><a href="accueil.php">← Retour à l'accueil</a></p>
    </div>
</div>
</body>
</html>