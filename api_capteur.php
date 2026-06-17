<?php
// api_capteur.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'core/Model.php';

// Clé secrète pour sécuriser l'API
$CLE_SECRETE = "g5d_escape_2024";

$data = json_decode(file_get_contents('php://input'), true);

// Vérification clé
if (!isset($data['cle']) || $data['cle'] !== $CLE_SECRETE) {
    http_response_code(403);
    echo json_encode(['erreur' => 'Accès refusé']);
    exit;
}

$etat = $data['etat']; // "LIGHT_ON" ou "LIGHT_OFF"
$valeur = $data['valeur']; // valeur brute ADC

try {
    $pdo = new PDO(
        "mysql:host=node.solyzon.com;port=3307;dbname=escapegame_G5B;charset=utf8",
        "escapegame_G5B", "JYm5co*JAp..K(U]"
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Insère le log dans G5D_capteur_logs
    $stmt = $pdo->prepare("
        INSERT INTO G5D_capteur_logs (valeur, date_mesure, capteur, unite)
        VALUES (:valeur, NOW(), 'LDR', 'ADC')
    ");
    $stmt->execute([':valeur' => $valeur]);

    // Met à jour la progression globale si lumière allumée
    if ($etat === 'LIGHT_ON') {
        $stmt2 = $pdo->prepare("
            UPDATE progression SET progress = 100
            WHERE salle = 'G5D'
        ");
        $stmt2->execute();
    }

    echo json_encode(['succes' => true, 'etat' => $etat]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['erreur' => $e->getMessage()]);
}
?>