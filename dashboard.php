<?php
session_start();
require_once 'config/database.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$stmt = $pdo->prepare("SELECT salle_electricite FROM G5D_progression WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$progress = $stmt->fetch();
$currentProgress = $progress ? $progress['salle_electricite'] : 0;
$message = '';

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['answer'])) {
    $answer = strtolower(trim($_POST['answer']));
    $correctAnswers = ['electricite', 'l\'electricité', 'le courant', 'courant', 'l\'energie', 'energie', 'électricité'];
    
    if(in_array($answer, $correctAnswers)) {
        $newProgress = min(100, $currentProgress + 25);
        updateProgress($_SESSION['user_id'], $newProgress);
        $currentProgress = $newProgress;
        if($newProgress == 100) {
            $message = '<div class="success-message">🏆 FÉLICITATIONS ! Salle terminée à 100% ! 🏆</div>';
        } else {
            $message = '<div class="success-message">✨ Bonne réponse ! Progression +25% ✨</div>';
        }
    } else {
        $message = '<div class="error-message">❌ Mauvaise réponse, réessayez ! ❌</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salle Électricité - Escape Game G5D</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="room-dashboard">
    <nav class="glass-nav">
        <div class="logo">
            <span class="logo-icon">⚡</span>
            <span class="logo-text">Escape Game - G5D</span>
        </div>
        <div class="nav-links">
            <span class="user-greeting">👤 <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="logout.php" class="nav-btn">Déconnexion</a>
        </div>
    </nav>

    <div class="dashboard-container">
        <div class="room-title">
            <h1>SALLE ÉLECTRICITÉ</h1>
            <div class="cadena-badge">
                <span>🔓</span>
                <span>Cadenas ouvert - Salle accessible</span>
            </div>
        </div>

        <div class="global-progress-card">
            <div class="progress-label">
                <span>Progression de la salle</span>
                <span><?php echo $currentProgress; ?>%</span>
            </div>
            <div class="progress-bar-big">
                <div class="progress-fill-big" style="width: <?php echo $currentProgress; ?>%;">
                    <?php if($currentProgress > 0): ?><?php echo $currentProgress; ?>%<?php endif; ?>
                </div>
            </div>
        </div>

        <?php echo $message; ?>

        <?php if($currentProgress < 100): ?>
            <div class="enigma-card">
                <h2>🔐 Énigme</h2>
                <div class="enigma-text">
                    "Je suis invisible mais je me fais sentir,<br>
                    Sans moi plus rien ne peut s'allumer.<br>
                    Je circule partout mais on ne peut me voir,<br>
                    Je suis la force qui fait tout bouger."
                </div>
                <div class="hint">💡 Indice : Ça fait tourner les ampoules et les moteurs...</div>
                
                <form method="POST" class="answer-form">
                    <input type="text" name="answer" placeholder="Votre réponse..." required autocomplete="off">
                    <button type="submit">Vérifier</button>
                </form>
            </div>
        <?php else: ?>
            <div class="enigma-card completion-card">
                <div class="trophy">🏆</div>
                <h2>Mission accomplie !</h2>
                <p>Vous avez maîtrisé la salle Électricité à 100% !</p>
                <p>La lumière est allumée, vous pouvez passer à la prochaine salle.</p>
                <div style="font-size: 1.5rem; margin-top: 20px;">✨ ⚡ ✨</div>
            </div>
        <?php endif; ?>

        <div style="text-align: center;">
            <a href="index.php" class="back-link">← Retour à l'accueil</a>
        </div>
    </div>
</body>
</html>