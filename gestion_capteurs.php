<?php
session_start();

if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des capteurs</title>

    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="style.css">
</head>

<body class="room-dashboard">


    <div class="nav-links">
        <a href="dashboard.php" class="nav-btn">
            Retour salle
        </a>

        <a href="logout.php" class="nav-btn">
            Déconnexion
        </a>
    </div>
</nav>

<nav class="glass-nav">
    <div class="logo">
        <span class="logo-icon">⚡</span>
        <span class="logo-text">Escape Game - G5D</span>
    </div>
    <div class="nav-center">
        <a href="/accueil.php" class="nav-btn">🏠 Accueil</a>
        <a href="/gestion_capteurs.php" class="nav-btn">⚙️ Capteurs</a>
        <a href="/dashboard.php" class="nav-btn">📊 Données</a>
    </div>
    <div class="nav-links">
        <?php if(isset($_SESSION['user_id'])): ?>
            <span class="user-greeting">👤 <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="/logout.php" class="nav-btn">Déconnexion</a>
        <?php else: ?>
            <a href="/login.php" class="nav-btn">Connexion</a>
        <?php endif; ?>
    </div>
</nav>

<div class="dashboard-container">

    <div class="room-title">
        <h1>GESTION DES CAPTEURS ET ACTIONNEURS</h1>
    </div>

    <details class="capteur-card">
        <summary>État du capteur de lumière (LDR)</summary>

        <div class="info-row">
            <span class="info-label">Nom</span>
            <span class="info-value">LDR</span>
        </div>

        <div class="info-row">
            <span class="info-label">État actuel</span>
            <span class="info-value">Obscurité détectée</span>
        </div>

        <div class="info-row">
            <span class="info-label">Valeur mesurée</span>
            <span class="info-value">120 lux</span>
        </div>
    </details>



    <details class="capteur-card">
        <summary>Actionneur LED</summary>

        <div class="info-row">
            <span class="info-label">Nom</span>
            <span class="info-value">LED</span>
        </div>

        <div class="info-row">
            <span class="info-label">État actuel</span>
            <span class="info-value">Éteinte</span>
        </div>

        <div class="info-row">
            <span class="info-label">Mode</span>
            <span class="info-value">Automatique</span>
        </div>
    </details>

    <details class="capteur-card">
        <summary>Conditions de déverrouillage</summary>

        <label class="condition-line">
            <input type="checkbox" checked disabled>
            Capteur dans l'obscurité
        </label>

        <label class="condition-line">
            <input type="checkbox" disabled>
            Énigme 1 résolue
        </label>

        <label class="condition-line">
            <input type="checkbox" disabled>
            Énigme 2 résolue
        </label>

        <label class="condition-line">
            <input type="checkbox" disabled>
            Énigme 3 résolue
        </label>

        <label class="condition-line">
            <input type="checkbox" disabled>
            Énigme 4 résolue
        </label>


    </details>

</div>

</body>
</html>
