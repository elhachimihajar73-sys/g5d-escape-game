<?php
error_reporting(0);
ini_set('display_errors', 0);

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../app/models/Indice.php';
require_once __DIR__ . '/../../app/models/Salle.php';

class ElectricityController extends Controller {

    // Affiche la page principale avec les 4 énigmes
    public function index() {
        $indiceModel = new Indice();
        $enigmes = $indiceModel->getAll();
        $this->render('electricity/index', ['enigmes' => $enigmes]);
    }

    // Appelé quand on clique sur "Valider"
    public function validerCode() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        $code = $data['code'] ?? '';

        $salleModel = new Salle();
        $correct = $salleModel->verifierCode($code);
        $salleModel->enregistrerTentative($code, $correct);

        if ($correct) {
            $salleModel->updateProgression(100);
        }

        echo json_encode(['succes' => $correct]);
        exit;
    }

    // Appelé quand on clique sur "Activer la lumière"
    public function activerLed() {
        header('Content-Type: application/json');

        try {
            $pdo = new PDO(
                "mysql:host=node.solyzon.com;port=3307;dbname=escapegame_G5B;charset=utf8",
                "escapegame_G5B", "JYm5co*JAp..K(U]",
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            $stmt = $pdo->prepare("UPDATE progression SET progress = 100 WHERE salle = 'G5D'");
            $stmt->execute();

            echo json_encode(['succes' => true]);
        } catch (Exception $e) {
            echo json_encode(['erreur' => $e->getMessage()]);
        }
        exit;
    }
}