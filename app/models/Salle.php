<?php
require_once __DIR__ . '/../../core/Model.php';

class Salle extends Model {

    // Vérifie si le code saisi est le bon (9781)
    public function verifierCode($code) {
        return $code === '9781'; // retourne true ou false
    }

    // Enregistre chaque tentative dans la BDD
    public function enregistrerTentative($code, $correct) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO G5D_tentatives (code_saisi, correct) VALUES (?, ?)"
        );
        $stmt->execute([$code, $correct ? 1 : 0]); // 1 = correct, 0 = incorrect
    }

    // Met à jour la progression de G5D à 100% quand code correct
    public function updateProgression($valeur) {
        $stmt = $this->pdo->prepare(
            "UPDATE progression SET progress = ? WHERE salle = 'G5D'"
        );
        $stmt->execute([$valeur]);
    }
}