<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Electricity — G5D</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/g5d-escape-game/public/css/electricity.css">
    <style>
        :root { --primary: #00ff9d; --dark: #0a0a0f; --glass: rgba(10,10,15,0.8); --glass-border: rgba(0,255,157,0.2); }
        body { font-family: 'Poppins', sans-serif; background: radial-gradient(ellipse at center, #0d0d1a 0%, #05050a 100%); min-height: 100vh; color: white; }
        .glass-nav { position: fixed; top: 20px; left: 20px; right: 20px; background: var(--glass); backdrop-filter: blur(12px); border-radius: 60px; padding: 12px 30px; display: flex; justify-content: space-between; align-items: center; border: 1px solid var(--glass-border); z-index: 100; box-shadow: 0 8px 32px rgba(0,0,0,0.3); }
        .logo { display: flex; align-items: center; gap: 10px; font-family: 'Orbitron', monospace; font-size: 1.2rem; font-weight: bold; }
        .logo-icon { font-size: 1.6rem; }
        .logo-text { background: linear-gradient(135deg, var(--primary), #00ffcc); -webkit-background-clip: text; background-clip: text; color: transparent; }
        .nav-center { display: flex; gap: 10px; position: absolute; left: 50%; transform: translateX(-50%); }
        .nav-links { display: flex; gap: 15px; align-items: center; }
        .nav-btn { padding: 8px 22px; border-radius: 40px; text-decoration: none; color: white; transition: all 0.3s ease; font-weight: 500; font-size: 0.9rem; }
        .nav-btn:hover { background: var(--primary); color: var(--dark); transform: translateY(-2px); }
        .user-greeting { color: var(--primary); font-size: 0.9rem; }
        .alert-bar { margin-top: 80px; }
        .container { max-width: 800px; margin: 30px auto 40px; padding: 20px; text-align: center; }
    </style>
</head>
<body>

<?php require_once __DIR__ . '/../../../navbar.php'; ?>

<div class="alert-bar">
    <span class="dot"></span> ALERT <span class="dot"></span>
    <p>Système énergétique instable.</p>
    <div class="timer" id="timer">15:00</div>
</div>

<div class="container">
    <h1>RÉACTEUR HORS LIGNE</h1>
    <p class="intro">Pour rétablir l'alimentation, vous devez entrer le code d'activation correct.</p>

    <div class="instruction-hint">
        ★ Instruction : Prenez le dernier chiffre de chaque réponse aux énigmes et assemblez-les pour former le code.
    </div>

    <div class="indices-grid">
        <?php foreach ($enigmes as $e): ?>
            <button class="indice-btn" onclick="afficherIndice(<?= $e['numero'] ?>, '<?= htmlspecialchars($e['question']) ?>')">
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

<script src="/g5d-escape-game/public/js/electricity.js"></script>
</body>
</html>