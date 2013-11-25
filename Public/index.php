<?php
header('Content-type: text/html; charset=UTF-8');

//we need to setup the include path for ViewScript including relative to outside the web root
$upOneLevel = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
set_include_path(get_include_path() . PATH_SEPARATOR . $upOneLevel . PATH_SEPARATOR . $upOneLevel . 'Tvc');

//Create an instance of our loader.
require('../vendor/autoload.php');
use Framework\Application;

//create an application instance to pass down via DI
$application = new Application();

//handle URI routing
$router = Framework\Router::getInstance();
$uriController = 'Controller\\'.$router->getController();

ob_start();

//create controller
if (class_exists($uriController)) {

    $injector = new \tomverran\di\Injector();
    $injector->bind($application);

    $instance = $injector->resolve($uriController);

    //invoke our action method on our controller
    if (is_callable(array($instance, $router->getMethod()))) {
        call_user_func_array(array($instance, $router->getMethod()), array());
        $application->fire(Application::SUCCESS);
    }
}

//basic 404 page
if (!$application->fired(Application::SUCCESS)) {
    $application->fire(Application::NOT_FOUND);
}