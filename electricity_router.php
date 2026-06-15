<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/Router.php';
$router = new Router();
$router->dispatch();