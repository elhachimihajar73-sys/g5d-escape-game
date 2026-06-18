<?php
session_start();
require_once 'config/database.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$stmt = $pdo->query("SELECT * FROM G5D_capteur_logs WHERE capteur = 'LDR' ORDER BY date_mesure DESC LIMIT 1");
$lastLdr = $stmt->fetch();

$valeurLdr = $lastLdr ? $lastLdr['valeur'] : null;
$uniteLdr = $lastLdr ? $lastLdr['unite'] : 'ADC';

if($valeurLdr !== null && $valeurLdr > 2000) {
    $etatLdr = "Obscurité détectée";
} else {
    $etatLdr = "Lumière détectée";
}

$progress = getProgress($_SESSION['user_id']);

$capteurObscurite = ($etatLdr === "Obscurité détectée");
$enigmeResolue = $progress && $progress['salle_electricite'] >= 100;
$systemeDeverrouille = $capteurObscurite && $enigmeResolue;

$etatLed = $systemeDeverrouille ? "Allumée" : "Éteinte";
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
            <span class="info-value"><?php echo $etatLdr; ?></span>

        </div>

        <div class="info-row">
            <span class="info-label">Valeur mesurée</span>
            <span class="info-value">
                <?php echo $valeurLdr !== null ? $valeurLdr . ' ' . htmlspecialchars($uniteLdr) : 'Aucune donnée'; ?>
            </span>
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
            <span class="info-value"><?php echo $etatLed; ?></span>
        </div>

        <div class="info-row">
            <span class="info-label">Mode</span>
            <span class="info-value">Automatique</span>
        </div>
    </details>

    <details class="capteur-card">
        <summary>Conditions de déverrouillage</summary>

        <label class="condition-line">
            <input type="checkbox" <?php echo $capteurObscurite ? 'checked' : ''; ?> disabled>
            Capteur dans l'obscurité
        </label>

        <label class="condition-line">
            <input type="checkbox" <?php echo $enigmeResolue ? 'checked' : ''; ?> disabled>
            Énigme résolue
        </label>

        <label class="condition-line">
            <input type="checkbox" <?php echo $systemeDeverrouille ? 'checked' : ''; ?> disabled>
            Système déverrouillé
        </label>
    </details>

</div>

</body>
</html>
