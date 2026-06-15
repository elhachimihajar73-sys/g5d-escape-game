<?php if(session_status() === PHP_SESSION_NONE) session_start(); ?>
<nav class="glass-nav">
    <div class="logo">
        <span class="logo-icon">⚡</span>
        <span class="logo-text">Escape Game - G5D</span>
    </div>
    <div class="nav-center">
        <a href="/g5d-escape-game/accueil.php" class="nav-btn">🏠 Accueil</a>
        <a href="/g5d-escape-game/gestion_capteurs.php" class="nav-btn">⚙️ Capteurs</a>
        <a href="/g5d-escape-game/dashboard.php" class="nav-btn">📊 Données</a>
    </div>
    <div class="nav-links">
        <?php if(isset($_SESSION['user_id'])): ?>
            <span class="user-greeting">👤 <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="/g5d-escape-game/logout.php" class="nav-btn">Déconnexion</a>
        <?php else: ?>
            <a href="/g5d-escape-game/login.php" class="nav-btn">Connexion</a>
        <?php endif; ?>
    </div>
</nav>