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
$subscribers = array();

//include Subscribers from a config file
require('Application/Config/Subscribers.php');
foreach ($subscribers as $subscriber) {
    $dispatcher->addSubscriber($subscriber);
}

//create our DI injector and bind the above instances
$injector = new \tomverran\di\Injector();
$injector->bind($dispatcher);
$injector->bind($request);

//dispatch the event to give any application subscribers a chance to bind dependencies
$dispatcher->dispatch('tvc.bind', new \Framework\Event\BindClassesEvent($injector));

//finally create the controller resolver
$paramResolver = new \TomVerran\ContainerParameterResolver( $injector );
$resolver = new \Framework\Resolver( $injector, $paramResolver );

//create our "HTTP Kernel" that uses the dispatcher & resolver to get things done
$kernel = new \Symfony\Component\HttpKernel\HttpKernel($dispatcher, $resolver);
$response = $kernel->handle($request);
$response->send();

//fire the terminate event
$kernel->terminate($request, $response);