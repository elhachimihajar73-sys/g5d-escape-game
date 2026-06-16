<?php
session_start();
require_once 'config/database.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Récupère les dernières données capteurs
$stmt = $pdo->query("SELECT * FROM G5D_progression");
$données = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Données — G5D</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #00ff9d; --dark: #0a0a0f; --glass: rgba(10,10,15,0.8); --glass-border: rgba(0,255,157,0.2); }
        * { margin: 0; padding: 0; box-sizing: border-box; }
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
        .container { max-width: 900px; margin: 120px auto 40px; padding: 20px; }
        h1 { font-family: 'Orbitron', monospace; text-align: center; font-size: 2rem; letter-spacing: 4px; margin-bottom: 40px; color: var(--primary); }
        .data-card { background: var(--glass); border: 1px solid var(--glass-border); border-radius: 20px; padding: 25px; margin-bottom: 20px; }
        .data-card h2 { font-size: 1rem; color: var(--primary); margin-bottom: 15px; font-family: 'Orbitron', monospace; }
        table { width: 100%; border-collapse: collapse; }
        th { background: rgba(0,255,157,0.1); color: var(--primary); padding: 12px; text-align: left; border-bottom: 1px solid var(--glass-border); }
        td { padding: 12px; border-bottom: 1px solid rgba(255,255,255,0.05); color: #ccc; }
        tr:hover td { background: rgba(0,255,157,0.05); }
        .progress-bar { height: 8px; background: rgba(255,255,255,0.1); border-radius: 4px; overflow: hidden; margin-top: 5px; }
        .progress-fill { height: 100%; background: linear-gradient(90deg, var(--primary), #00ffcc); border-radius: 4px; }
        .refresh-info { text-align: center; color: #666; font-size: 0.8rem; margin-top: 20px; }
    </style>
</head>
<body>

<?php require_once 'navbar.php'; ?>

<div class="container">
    <h1>📊 DONNÉES EN TEMPS RÉEL</h1>

    <div class="data-card">
        <h2>⚡ PROGRESSION DES SALLES</h2>
        <table>
            <tr>
                <th>Salle</th>
                <th>Progression</th>
                <th>Barre</th>
            </tr>
            <?php foreach($données as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['salle']) ?></td>
                    <td><?= $row['progress'] ?>%</td>
                    <td>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: <?= $row['progress'] ?>%"></div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <p class="refresh-info">🔄 Page rafraîchie automatiquement toutes les 10 secondes</p>
</div>

<script>
    setTimeout(() => location.reload(), 10000);
</script>
</body>
</html>