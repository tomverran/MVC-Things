<?php

use Framework\Resolver;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\HttpKernel;
use TomVerran\Di\AggregateContainer;
use TomVerran\Di\Registry\InterfaceRegistry;
use TomVerran\Di\Registry\SingletonRegistry;

$upOneLevel = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
set_include_path(get_include_path() . PATH_SEPARATOR . $upOneLevel . PATH_SEPARATOR . $upOneLevel . 'Tvc');

//Create an instance of our loader.
require('../vendor/autoload.php');

$request = Request::createFromGlobals();
$injector = new AggregateContainer;
$dispatcher = new EventDispatcher;

/** @var InterfaceRegistry $interfaces */
$interfaces = $injector->get( InterfaceRegistry::class );
$interfaces->add( ControllerResolverInterface::class, Resolver::class );

/** @var SingletonRegistry $singletons */
$singletons = $injector->get( SingletonRegistry::class );
$singletons->add( EventDispatcherInterface::class, $dispatcher );
$singletons->add( Request::class, $request );

/** @var HttpKernel $kernel */
$kernel = $injector->get( HttpKernel::class );
$response = $kernel->handle( $request );
$response->send();

//fire the terminate event
$kernel->terminate( $request, $response );