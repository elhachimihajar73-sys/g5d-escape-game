<?php
$host = 'node.solyzon.com';
$port = '3307';
$dbname = 'escapegame_G5B';
$username = 'escapegame_G5B';
$password = 'JYm5co*JAp..K(U]';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Fonctions pour les autres salles
function updateProgress($userId, $progression) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE G5D_progression SET salle_electricite = ? WHERE user_id = ?");
    return $stmt->execute([$progression, $userId]);
}

function getProgress($userId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM G5D_progression WHERE user_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch();
}

// Fonction pour le MVC G5D
function getDB() {
    global $pdo;
    return $pdo;
}
