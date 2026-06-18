<?php
error_reporting(0);
ini_set('display_errors', 0);

if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/core/Router.php';
$router = new Router();
$router->dispatch();