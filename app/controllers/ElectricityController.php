<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../app/models/Indice.php';
require_once __DIR__ . '/../../app/models/Salle.php';

class ElectricityController extends Controller {

    // Affiche la page principale avec les 4 énigmes
    public function index() {
        $indiceModel = new Indice();
        $enigmes = $indiceModel->getAll(); // récupère les questions depuis la BDD
        $this->render('electricity/index', ['enigmes' => $enigmes]); // envoie à la vue
    }

    // Appelé quand on clique sur "Valider"
    public function validerCode() {
        header('Content-Type: application/json'); // réponse en JSON pour le JavaScript
        $data = json_decode(file_get_contents('php://input'), true); // lit ce qu'envoie le JS
        $code = $data['code'] ?? '';

        $salleModel = new Salle();
        $correct = $salleModel->verifierCode($code); // vérifie le code
        $salleModel->enregistrerTentative($code, $correct); // enregistre en BDD

        if ($correct) {
            $salleModel->updateProgression(100); // met la progression à 100%
        }

        echo json_encode(['succes' => $correct]); // envoie true ou false au JavaScript
        exit;
    }
}