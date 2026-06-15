<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Electricity — G5D</title>
    <link rel="stylesheet" href="/g5d-escape-game/public/css/electricity.css">
</head>
<body>

<!-- BARRE D'ALERTE avec le timer -->
<div class="alert-bar">
    <span class="dot"></span> ALERT <span class="dot"></span>
    <p>Système énergétique instable.</p>
    <div class="timer" id="timer">15:00</div> <!-- compte à rebours -->
</div>

<div class="container">
    <h1>RÉACTEUR HORS LIGNE</h1>
    <p class="intro">Pour rétablir l'alimentation, vous devez entrer le code d'activation correct.</p>

    <div class="instruction-hint">
        ★ Instruction : Prenez le dernier chiffre de chaque réponse aux énigmes et assemblez-les pour former le code.
    </div>

    <!-- LES 4 BOUTONS INDICES générés depuis la BDD -->
    <div class="indices-grid">
        <?php foreach ($enigmes as $e): ?>
            <button class="indice-btn" onclick="afficherIndice(<?= $e['numero'] ?>, '<?= htmlspecialchars($e['question']) ?>')">
                Indice <?= $e['numero'] ?>
            </button>
        <?php endforeach; ?>
    </div>

    <!-- Zone où la question s'affiche quand on clique sur un indice -->
    <div id="indice-display" class="indice-display hidden"></div>

    <!-- CODE PIN : 4 cases rouges par défaut -->
    <div class="pin-section">
        <p>code PIN</p>
        <div class="pin-inputs">
            <!-- maxlength=1 : une seule lettre par case -->
            <!-- oninput : passe automatiquement à la case suivante -->
            <input type="text" maxlength="1" class="pin-case rouge" id="p1" oninput="focusNext(this,'p2')">
            <input type="text" maxlength="1" class="pin-case rouge" id="p2" oninput="focusNext(this,'p3')">
            <input type="text" maxlength="1" class="pin-case rouge" id="p3" oninput="focusNext(this,'p4')">
            <input type="text" maxlength="1" class="pin-case rouge" id="p4">
        </div>
        <button class="valider-btn" onclick="validerCode()">Valider</button>
    </div>

    <!-- Message succès ou erreur -->
    <div id="message-resultat" class="message hidden"></div>

    <!-- BOUTON POUSSOIR : désactivé par défaut -->
    <div class="bouton-section">
        <button id="bouton-poussoir" class="bouton-poussoir locked" disabled>
             ACTIVER LA LUMIÈRE
        </button>
    </div>
</div>

<!-- OVERLAY qui apparaît si le temps est écoulé -->
<div id="overlay-bloque" class="overlay hidden">
    <div class="overlay-content">
        <h2> Temps écoulé !</h2>
        <p>Le réacteur est définitivement hors ligne.</p>
    </div>
</div>

<script src="/g5d-escape-game/public/js/electricity.js"></script>
</body>
</html>