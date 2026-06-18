<?php
session_start();
require_once 'config/database.php';

// Optionnel : restreindre aux admins
// if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') { die('Accès refusé'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = Database::getInstance();
        $pdo = $db->getConnection();

        $stmt = $pdo->prepare("UPDATE progression SET progress = 0 WHERE salle = 'G5D'");
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Progression réinitialisée']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}