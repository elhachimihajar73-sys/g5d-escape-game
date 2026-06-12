<?php
require_once __DIR__ . '/../../core/Model.php'; // hérite de Model

class Indice extends Model {
    // Récupère les 4 énigmes depuis la BDD
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM G5D_enigmes ORDER BY numero");
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // retourne un tableau avec toutes les questions
    }
}