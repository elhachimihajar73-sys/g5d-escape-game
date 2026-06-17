<?php
class Model {
    protected $pdo;

    public function __construct() {
        $host = 'node.solyzon.com';
        $port = '3307';
        $dbname = 'escapegame_G5B';
        $username = 'escapegame_G5B';
        $password = 'JYm5co*JAp..K(U]';

        try {
            $this->pdo = new PDO(
                "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8",
                $username,
                $password
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            die("Erreur de connexion BDD : " . $e->getMessage());
        }
    }
}