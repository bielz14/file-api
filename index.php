<?php

require 'vendor/autoload.php';

//? session do not used api and front
session_start();

//execute botstrap
bootstrapExecute();

if ($_GET['controller']) {
    $controllerName = ucfirst($_GET['controller']);
} else {
    $controllerName = 'Index';
}

if ($_GET['action']) {
    $actionName = $_GET['action'];
} else {
    $actionName = lcfirst('index');
}

$controllerClass = 'Api\Controllers\\' . $controllerName . 'Controller'; //initialization to controller class name
$controller = new $controllerClass(); //initialization to controller object
$controller->{'action' . $actionName}(); //execute to action in current controller