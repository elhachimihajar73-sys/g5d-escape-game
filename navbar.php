<?php if(session_status() === PHP_SESSION_NONE) session_start(); ?>
<nav class="glass-nav">
    <div class="logo">
        <span class="logo-icon">⚡</span>
        <span class="logo-text">Escape Game - G5D</span>
    </div>
    <div class="nav-center">
        <a href="?page=accueil" class="nav-btn">🏠 Accueil</a>
        <a href="?page=capteurs" class="nav-btn">📡 Capteurs</a>
        <a href="?page=dashboard" class="nav-btn">📊 Données</a>
    </div>
    <div class="nav-links">
        <?php if(isset($_SESSION['user_id'])): ?>
            <span class="user-greeting">👤 <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="logout.php" class="nav-btn">Déconnexion</a>
        <?php else: ?>
            <a href="login.php" class="nav-btn">Connexion</a>
        <?php endif; ?>
    </div>
</nav>