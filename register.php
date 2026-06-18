<?php
session_start();
require_once 'config/database.php';

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    
    if(empty($username) || empty($email) || empty($password)) {
        $error = "Tous les champs sont obligatoires";
    } elseif($password !== $confirm) {
        $error = "Les mots de passe ne correspondent pas";
    } elseif(strlen($password) < 4) {
        $error = "Le mot de passe doit faire au moins 4 caractères";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            $check = $pdo->prepare("SELECT id FROM G5D_users WHERE username = ? OR email = ?");
            $check->execute([$username, $email]);
            
            if($check->fetch()) {
                $error = "Nom d'utilisateur ou email déjà utilisé";
            } else {
                $pdo->beginTransaction();
                $stmt = $pdo->prepare("INSERT INTO G5D_users (username, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$username, $email, $hashed]);
                $userId = $pdo->lastInsertId();
                
                $stmt2 = $pdo->prepare("INSERT INTO G5D_progression (user_id) VALUES (?)");
                $stmt2->execute([$userId]);
                $pdo->commit();
                
                $success = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
            }
        } catch(PDOException $e) {
            $pdo->rollBack();
            $error = "Erreur: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Escape Game G5D</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/style.css">
</head>
<body class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-icon">📝</div>
            <h1>Inscription</h1>
            <p>Rejoignez l'aventure électrique</p>
        </div>
        
        <?php if($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if($success): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="input-group">
                <label>Nom d'utilisateur</label>
                <input type="text" name="username" required placeholder="Choisissez un pseudo">
            </div>
            
            <div class="input-group">
                <label>Email</label>
                <input type="email" name="email" required placeholder="votre@email.com">
            </div>
            
            <div class="input-group">
                <label>Mot de passe</label>
                <input type="password" name="password" required placeholder="Minimum 4 caractères">
            </div>
            
            <div class="input-group">
                <label>Confirmer le mot de passe</label>
                <input type="password" name="confirm_password" required placeholder="Retapez votre mot de passe">
            </div>
            
            <button type="submit" class="auth-btn">S'inscrire →</button>
        </form>
        
        <div class="auth-footer">
            <p>Déjà inscrit ? <a href="login.php">Se connecter</a></p>
            <p style="margin-top: 10px;"><a href="accueil.php">← Retour à l'accueil</a></p>
        </div>
    </div>
</body>
</html>