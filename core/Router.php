<?php
class Router {
    public function dispatch() {
        $page = $_GET['page'] ?? 'login';
        $action = $_GET['action'] ?? 'index';

        switch ($page) {
            case 'login':
                require_once __DIR__ . '/../login.php';
                break;

            case 'register':
                require_once __DIR__ . '/../register.php';
                break;

            case 'accueil':
                require_once __DIR__ . '/../accueil.php';
                break;

            case 'electricity':
                require_once __DIR__ . '/../app/controllers/ElectricityController.php';
                $controller = new ElectricityController();
                if ($action === 'valider') {
                    $controller->validerCode();
                } else {
                    $controller->index();
                }
                break;

            case 'capteurs':
                require_once __DIR__ . '/../gestion_capteurs.php';
                break;

            case 'dashboard':
                require_once __DIR__ . '/../dashboard.php';
                break;

            default:
                require_once __DIR__ . '/../login.php';
        }
    }
}