<?php

require __DIR__ .'/vendor/autoload.php';

use CoolForm\IndexController;

session_start();

$controller = new IndexController();
?>

<h1>Processing</h1>
