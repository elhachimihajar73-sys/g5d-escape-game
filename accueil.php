<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'config/database.php';

$userProgress = null;
if(isset($_SESSION['user_id'])) {
    $userProgress = getProgress($_SESSION['user_id']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escape Game - G5D</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary: #00ff9d;
            --primary-dark: #00cc7d;
            --dark: #0a0a0f;
            --glass: rgba(10, 10, 15, 0.8);
            --glass-border: rgba(0, 255, 157, 0.2);
        }
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
        .main-container { padding-top: 120px; padding-bottom: 60px; max-width: 1200px; margin: 0 auto; text-align: center; }
        .hero { text-align: center; margin-bottom: 60px; }
        .glitch { font-family: 'Orbitron', monospace; font-size: 3rem; font-weight: 900; text-transform: uppercase; text-shadow: 0.05em 0 0 rgba(255,0,255,0.4), -0.05em -0.025em 0 rgba(0,255,255,0.4); animation: glitch 3s infinite; letter-spacing: 4px; }
        @keyframes glitch {
            0%, 100% { text-shadow: 0.05em 0 0 rgba(255,0,255,0.4), -0.05em -0.025em 0 rgba(0,255,255,0.4); }
            25% { text-shadow: -0.05em -0.025em 0 rgba(255,0,255,0.4), 0.025em 0.05em 0 rgba(0,255,255,0.4); }
            50% { text-shadow: 0.025em 0.05em 0 rgba(255,0,255,0.4), 0.05em 0 0 rgba(0,255,255,0.4); }
            75% { text-shadow: -0.025em 0 0 rgba(255,0,255,0.4), 0.025em 0.025em 0 rgba(0,255,255,0.4); }
        }
        .tagline { font-size: 1rem; color: var(--primary); font-weight: 400; letter-spacing: 2px; margin-top: 15px; }
        .energy-pulse { margin-top: 25px; display: flex; justify-content: center; gap: 8px; }
        .pulse-ring { width: 8px; height: 8px; background: var(--primary); border-radius: 50%; animation: pulse-ring 1.5s infinite; box-shadow: 0 0 10px var(--primary); }
        .pulse-ring:nth-child(2) { animation-delay: 0.5s; }
        .pulse-ring:nth-child(3) { animation-delay: 1s; }
        @keyframes pulse-ring { 0%, 100% { transform: scale(1); opacity: 1; } 50% { transform: scale(2); opacity: 0.3; } }
        .rooms-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 25px; padding: 20px; max-width: 1100px; margin: 0 auto; }
        .room-card { background: var(--glass); backdrop-filter: blur(12px); border-radius: 20px; padding: 20px 12px; text-align: center; border: 1px solid var(--glass-border); transition: all 0.3s ease; cursor: pointer; }
        .room-card:hover { transform: translateY(-8px); border-color: var(--primary); box-shadow: 0 0 25px rgba(0,255,157,0.3); }
        .room-card.electric { border: 2px solid var(--primary); box-shadow: 0 0 15px rgba(0,255,157,0.2); }
        .room-card.electric:hover { box-shadow: 0 0 30px rgba(0,255,157,0.5); }
        .room-icon { font-size: 2.5rem; margin-bottom: 12px; }
        .room-card h3 { font-size: 0.9rem; margin-bottom: 10px; font-family: 'Orbitron', monospace; font-weight: 600; }
        .lock-status { display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px; border-radius: 30px; font-size: 0.7rem; font-weight: 500; }
        .lock-status.unlocked { background: rgba(0,255,157,0.2); color: var(--primary); }
        .lock-status.locked { background: rgba(255,50,50,0.2); color: #ff6666; }
        .progress-mini { margin-top: 12px; padding: 0 5px; }
        .progress-mini-bar { height: 4px; background: rgba(255,255,255,0.15); border-radius: 4px; overflow: hidden; }
        .progress-mini-fill { height: 100%; background: linear-gradient(90deg, var(--primary), #00ffcc); border-radius: 4px; transition: width 0.3s; }
        .progress-mini-text { font-size: 0.6rem; color: var(--primary); margin-top: 5px; display: block; }
        .room-overlay { margin-top: 12px; padding: 5px; background: var(--primary); color: var(--dark); border-radius: 30px; font-size: 0.7rem; font-weight: bold; opacity: 0; transition: opacity 0.3s; }
        .room-card.electric:hover .room-overlay { opacity: 1; }
        @media (max-width: 1000px) { .rooms-grid { grid-template-columns: repeat(3, 1fr); } .nav-center { display: none; } }
        @media (max-width: 650px) { .rooms-grid { grid-template-columns: repeat(2, 1fr); } .glitch { font-size: 2rem; } }
        @media (max-width: 450px) { .rooms-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

<nav class="glass-nav">
    <div class="logo">
        <span class="logo-icon">⚡</span>
        <span class="logo-text">Escape Game - G5D</span>
    </div>
    <div class="nav-center">
        <a href="accueil.php" class="nav-btn">🏠 Accueil</a>
        <a href="gestion_capteurs.php" class="nav-btn">⚙️ Capteurs</a>
        <a href="dashboard.php" class="nav-btn">📊 Données</a>
    </div>
    <div class="nav-links">
        <?php if(isset($_SESSION['user_id'])): ?>
            <span class="user-greeting">👤 <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="logout.php" class="nav-btn">Déconnexion</a>
        <?php else: ?>
            <a href="login.php" class="nav-btn">Connexion</a>
            <a href="register.php" class="nav-btn">Inscription</a>
        <?php endif; ?>
    </div>
</nav>

<main class="main-container">
    <div class="hero">
        <h1 class="glitch">ESCAPE GAME</h1>
        <p class="tagline">⚡ RÉSOLVEZ NOTRE ÉNIGME POUR ALLUMER LA LUMIÈRE ⚡</p>
        <div class="energy-pulse">
            <div class="pulse-ring"></div>
            <div class="pulse-ring"></div>
            <div class="pulse-ring"></div>
        </div>
    </div>

    <div class="rooms-grid">
        <div class="room-card electric" onclick="goToRoom()">
            <div class="room-icon">⚡</div>
            <h3>Salle Électricité</h3>
            <div class="lock-status unlocked"><span>🔓</span><span>Ouvert</span></div>
            <?php if($userProgress): ?>
                <div class="progress-mini">
                    <div class="progress-mini-bar">
                        <div class="progress-mini-fill" style="width: <?php echo $userProgress['salle_electricite'] ?? 0; ?>%"></div>
                    </div>
                    <span class="progress-mini-text"><?php echo $userProgress['salle_electricite'] ?? 0; ?>%</span>
                </div>
            <?php endif; ?>
            <div class="room-overlay">Cliquez pour entrer →</div>
        </div>
        <div class="room-card">
            <div class="room-icon">📡</div>
            <h3>Salle Communication</h3>
            <div class="lock-status locked"><span>🔒</span><span>Fermé</span></div>
        </div>
        <div class="room-card">
            <div class="room-icon">💾</div>
            <h3>Salle Stockage</h3>
            <div class="lock-status locked"><span>🔒</span><span>Fermé</span></div>
        </div>
        <div class="room-card">
            <div class="room-icon">🎮</div>
            <h3>Salle Commandes</h3>
            <div class="lock-status locked"><span>🔒</span><span>Fermé</span></div>
        </div>
        <div class="room-card">
            <div class="room-icon">🌿</div>
            <h3>Salle Serres</h3>
            <div class="lock-status locked"><span>🔒</span><span>Fermé</span></div>
        </div>
    </div>
</main>

<script>
    function goToRoom() {
        <?php if(isset($_SESSION['user_id'])): ?>
        window.location.href = 'electricity_router.php?page=electricity';
        <?php else: ?>
        if(confirm('Veuillez vous connecter pour accéder à la salle Électricité')) {
            window.location.href = 'login.php';
        }
        <?php endif; ?>
    }
</script>
</body>
</html>
