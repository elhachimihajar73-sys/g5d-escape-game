<?php
class Router {
    public function dispatch() {
        // Lit l'URL : ?page=electricity
        $page = $_GET['page'] ?? 'electricity'; // si pas de page, electricity par défaut
        $action = $_GET['action'] ?? 'index';   // si pas d'action, index par défaut

        switch ($page) {
            case 'electricity':
                // Charge le controller de la salle electricity
                require_once __DIR__ . '/../app/controllers/ElectricityController.php';
                $controller = new ElectricityController();

                if ($action === 'valider') {
                    $controller->validerCode(); // appelé quand on valide le code PIN
                } else {
                    $controller->index(); // affiche la page principale
                }
                break;
            default:
                echo "Page introuvable.";
        }
    }
}