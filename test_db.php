<?php
require 'config/database.php';
require 'core/Model.php';
require 'app/models/Indice.php';
$m = new Indice();
var_dump($m->getAll());