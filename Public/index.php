<?php

//we need to setup the include path for ViewScript including relative to outside the web root
$upOneLevel = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
set_include_path(get_include_path() . PATH_SEPARATOR . $upOneLevel . PATH_SEPARATOR . $upOneLevel . 'Tvc');

//Create an instance of our loader.
require('../vendor/autoload.php');

//create a Symfony request object from PHP globals. This is pretty fancy.
$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();

//create an event dispatcher, also a Symfony thing to mediate events etc
$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();

//like Symfony we listen on kernel.request and do routing then
$dispatcher->addSubscriber(new \Framework\Router());

//really temporary 404 handling. To be removed!
$dispatcher->addListener('kernel.exception', function(\Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $e) {
    if ($e->getException() instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
        $e->setResponse( new \Symfony\Component\HttpFoundation\Response('Not Found') );
        return;
    }
    throw $e->getException();
});

//create our DI injector and bind the above instances
$injector = new \tomverran\di\Injector();
$injector->bind($dispatcher);
$injector->bind($request);

//create a configuration object which stores stuff grouped by classnames
$configuration = new \Framework\Configuration\ConfigurationIni(dirname(__FILE__).'/../Tvc', 'Config');

//return a decorated object with a default class name
$injector->bind(function($class, $for) use(&$configuration) {
    return new \Framework\Configuration\GroupDecorator($configuration, str_replace('\\', '_', $for));
}, 'Framework\Configuration\Configuration');

//finally create the controller resolver
$resolver = new \Framework\Resolver($injector);

//create our "HTTP Kernel" that uses the dispatcher & resolver to get things done
$kernel = new \Symfony\Component\HttpKernel\HttpKernel($dispatcher, $resolver);
$response = $kernel->handle($request);
$response->send();

//fire the terminate event
$kernel->terminate($request, $response);