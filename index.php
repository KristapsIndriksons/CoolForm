<?php

require __DIR__ .'/vendor/autoload.php';

use CoolForm\Controller\IndexController;

session_start();

$controller = new IndexController();
?>

<h1>Processing</h1>
