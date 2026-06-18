<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$pdo_config = [
    'host'   => 'node.solyzon.com',
    'port'   => '3307',
    'dbname' => 'escapegame_G5B',
    'user'   => 'escapegame_G5B',
    'pass'   => 'JYm5co*JAp..K(U]'
];

function getPDO($cfg) {
    $pdo = new PDO(
        "mysql:host={$cfg['host']};port={$cfg['port']};dbname={$cfg['dbname']};charset=utf8",
        $cfg['user'], $cfg['pass']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}

// GET ?action=get_commande → Python vérifie si LED doit s'allumer
if (isset($_GET['action']) && $_GET['action'] === 'get_commande') {
    try {
        $pdo = getPDO($pdo_config);
        $stmt = $pdo->prepare(
            "SELECT progress, led_activee FROM progression WHERE salle = 'G5D'"
        );
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // ✅ Double condition : code validé ET bouton cliqué
        $allumer = $row
            && (int)$row['progress']    === 100
            && (int)$row['led_activee'] === 1;

        echo json_encode(['allumer_led' => $allumer]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['erreur' => $e->getMessage()]);
    }
    exit;
}

// POST → Python envoie LDR OU joueur active le bouton
$CLE_SECRETE = "g5d_escape_2024";
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['cle']) || $data['cle'] !== $CLE_SECRETE) {
    http_response_code(403);
    echo json_encode(['erreur' => 'Accès refusé']);
    exit;
}

$etat   = $data['etat']   ?? '';
$valeur = $data['valeur'] ?? 0;

try {
    $pdo = getPDO($pdo_config);

    // Cas 1 : joueur clique "ACTIVER LA LUMIÈRE" → on set led_activee = 1
    if ($etat === 'ACTIVATE_LED') {
        $stmt = $pdo->prepare(
            "UPDATE progression SET led_activee = 1 WHERE salle = 'G5D'"
        );
        $stmt->execute();
        echo json_encode(['succes' => true, 'action' => 'led_activee']);
        exit;
    }

    // Cas 2 : reset (fin de partie) → remettre à zéro
    if ($etat === 'RESET') {
        $stmt = $pdo->prepare(
            "UPDATE progression SET led_activee = 0, progress = 0 WHERE salle = 'G5D'"
        );
        $stmt->execute();
        echo json_encode(['succes' => true, 'action' => 'reset']);
        exit;
    }

    // Cas 3 : log LDR normal
    $stmt = $pdo->prepare("
        INSERT INTO G5D_capteur_logs (valeur, date_mesure, capteur, unite)
        VALUES (:valeur, NOW(), 'LDR', 'ADC')
    ");
    $stmt->execute([':valeur' => $valeur]);

    echo json_encode(['succes' => true, 'etat' => $etat]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['erreur' => $e->getMessage()]);
}
?>