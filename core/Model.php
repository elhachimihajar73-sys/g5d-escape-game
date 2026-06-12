<?php
// On inclut le fichier de connexion BDD
require_once __DIR__ . '/../config/database.php';

// Classe parente que tous les Models vont hériter
class Model {

    // Variable qui va stocker la connexion BDD
    protected $pdo;

    // Dès qu'on crée un Model, on se connecte automatiquement à la BDD
    public function __construct() {
        $this->pdo = getDB(); // appelle la fonction dans database.php
    }
}