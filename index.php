<?php
spl_autoload_register(function($className) {

    require_once(__DIR__ . "/src/$className.php");

});

$controller = new Controller();

$data = $controller->action();

$template = new Template();

echo $template->show($data);