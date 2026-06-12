<?php
// Point d'entrée unique du site
// Toutes les URLs passent par ici

require_once __DIR__ . '/core/Router.php'; // charge le Router

$router = new Router();
$router->dispatch(); // lance le routage