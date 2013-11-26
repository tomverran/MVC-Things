<?php
/**
 * Resolve.php
 * @author Tom
 * @since 26/11/13
 */

namespace Framework;


use Framework\Exception\NotFound;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Process\Exception\LogicException;
use tomverran\di\Injector;

class Resolver implements ControllerResolverInterface
{
    /**
     * @var \tomverran\di\Injector
     */
    private $injector;

    /**
     * Construct this resolver with a DI injector
     * which is used to do the instantiation
     * @param Injector $injector
     */
    public function __construct( Injector $injector )
    {
        $this->injector = $injector;
    }

    /**
     * Returns the Controller instance associated with a Request.
     *
     * As several resolvers can exist for a single application, a resolver must
     * return false when it is not able to determine the controller.
     *
     * The resolver must only throw an exception when it should be able to load
     * controller but cannot because of some errors made by the developer.
     *
     * @param Request $request A Request instance
     *
     * @return callable|false A PHP callable representing the Controller,
     *                        or false if this resolver is not able to determine the controller
     *
     * @throws \Framework\Exception\NotFound If the controller can't be found
     */
    public function getController(Request $request)
    {
        //append on our controller namespace to find the actual class
        $class = 'Controller\\'.$request->attributes->get('controller');

        //if it exists, resolve it
        if (class_exists($class)  ) {
            $instance = $this->injector->resolve($class);
            if (is_callable($callable = array($instance, $request->attributes->get('method')))) {
                return $callable;
            }
        }

        throw new NotFound();
    }

    /**
     * Returns the arguments to pass to the controller.
     *
     * @param Request $request A Request instance
     * @param callable $controller A PHP callable
     *
     * @return array An array of arguments to pass to the controller
     *
     * @throws \RuntimeException When value for argument given is not provided
     */
    public function getArguments(Request $request, $controller)
    {
        return array();
    }
}