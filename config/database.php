<?php

define('DB_HOST', 'node.solyzon.com');
define('DB_PORT', '3307');
define('DB_NAME', 'escapegame_G5B');
define('DB_USER', 'escapegame_G5B');
define('DB_PASS', 'JYm5co*JAp..K(U]');

function getDB()
{
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8",
                DB_USER,
                DB_PASS
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur BDD : " . $e->getMessage());
        }
    }
    return $pdo;
}