<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Electricity — G5D</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/electricity.css">
</head>
<body>

<?php require_once __DIR__ . '/../../../navbar.php'; ?>

<div class="alert-bar" style="margin-top:80px;">
    <span class="dot" id="dot1"></span>
    ALERT
    <span class="dot" id="dot2"></span>
    <p>Système énergétique instable.</p>
    <div class="timer" id="timer">15:00</div>
</div>

<div class="container">
    <h1 id="titre-reacteur">RÉACTEUR HORS LIGNE</h1>
    <p class="intro">Pour rétablir l'alimentation, vous devez entrer le code d'activation correct.</p>

    <div class="instruction-hint">
        ★ Instruction : Prenez le dernier chiffre de chaque réponse aux énigmes et assemblez-les pour former le code.
    </div>

    <div class="indices-grid">
        <?php foreach ($enigmes as $e): ?>
            <button class="indice-btn" onclick="afficherIndice(<?= $e['numero'] ?>, `<?= addslashes($e['question']) ?>`)">
                Indice <?= $e['numero'] ?>
            </button>
        <?php endforeach; ?>
    </div>

    <div id="indice-display" class="indice-display hidden"></div>

    <div class="pin-section">
        <p>code PIN</p>
        <div class="pin-inputs">
            <input type="text" maxlength="1" class="pin-case rouge" id="p1" oninput="focusNext(this,'p2')">
            <input type="text" maxlength="1" class="pin-case rouge" id="p2" oninput="focusNext(this,'p3')">
            <input type="text" maxlength="1" class="pin-case rouge" id="p3" oninput="focusNext(this,'p4')">
            <input type="text" maxlength="1" class="pin-case rouge" id="p4">
        </div>
        <button class="valider-btn" onclick="validerCode()">Valider</button>
    </div>

    <div id="message-resultat" class="message hidden"></div>

    <div class="bouton-section">
        <button id="bouton-poussoir" class="bouton-poussoir locked" disabled>
            🔒 ACTIVER LA LUMIÈRE
        </button>
    </div>
</div>

<div id="overlay-bloque" class="overlay">
    <div class="overlay-content">
        <h2>⏰ Temps écoulé !</h2>
        <p>Le réacteur est définitivement hors ligne.</p>
    </div>
</div>

<script src="/public/js/electricity.js"></script>
</body>
</html>