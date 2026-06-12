<?php
class Controller {
    // Fonction qui affiche une vue (un fichier HTML/PHP)
    protected function render($view, $data = []) {
        extract($data); // transforme le tableau $data en variables utilisables dans la vue
        // Cherche le fichier vue dans app/views/
        require_once __DIR__ . '/../app/views/' . $view . '.php';
    }
}