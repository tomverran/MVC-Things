<?php
$phpClasses = get_declared_classes();
header('Content-type: text/html; charset=UTF-8');


//Create an instance of our loader.
require('vendor/autoload.php');

//we need to setup the include path for ViewScript including relative to here
set_include_path(get_include_path().PATH_SEPARATOR.getcwd());

//handle URI routing
$router = Framework\Router::getInstance();
$uriController = 'Controller\\'.$router->getController();

//create controller
if (class_exists($uriController)) {

    $rc = new ReflectionClass($uriController);
    $uriMethod = $router->getMethod();

    //invoke our action method on our controller
    if (!$rc->isAbstract() && $rc->hasMethod($uriMethod) && $rc->getMethod($uriMethod)->isPublic()) {
        $controller = $rc->newInstance();
        $rc->getMethod($uriMethod)->invokeArgs($controller,array());
        $ran = true;
    }
}

//basic 404 page
if (!isset($ran)) {
    header('HTTP/1.0 404 Not Found');
    echo '<h1>404</h1><p>page not found</p>';
}

var_dump(array_diff(get_declared_classes(), $phpClasses));

