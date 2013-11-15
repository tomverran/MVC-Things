<?php
header('Content-type: text/html; charset=UTF-8');

//we need to setup the include path for ViewScript including relative to outside the web root
$upOneLevel = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
set_include_path(get_include_path() . PATH_SEPARATOR . $upOneLevel . PATH_SEPARATOR . $upOneLevel . 'Tvc');

//Create an instance of our loader.
require('../vendor/autoload.php');

//handle URI routing
$router = Framework\Router::getInstance();
$uriController = 'Controller\\'.$router->getController();

//create controller
if (class_exists($uriController)) {

    $injector = new \tomverran\di\Injector();
    $instance = $injector->resolve($uriController);

    //invoke our action method on our controller
    if (is_callable(array($instance, $router->getMethod()))) {
        call_user_func_array(array($instance, $router->getMethod()), array());
        $ran = true;
    }
}

//basic 404 page
if (!isset($ran)) {
    header('HTTP/1.0 404 Not Found');
    echo '<h1>404</h1><p>page not found</p>';
}