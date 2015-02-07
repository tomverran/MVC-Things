<?php
/**
 * Resolve.php
 * @author Tom
 * @since 26/11/13
 */

namespace Framework;

use Interop\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use ReflectionParameter;

class Resolver implements ControllerResolverInterface
{
    /**
     * @var ContainerInterface
     */
    private $injector;

    /**
     * Construct this resolver with a DI injector
     * which is used to do the instantiation
     * @param ContainerInterface $injector
     */
    public function __construct( ContainerInterface $injector )
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
     */
    public function getController( Request $request )
    {
        //append on our controller namespace to find the actual class
            $class = 'Controller\\'.ucfirst( strtolower( $request->attributes->get('controller') ) );

        //if it exists, resolve it
        if (class_exists($class)  ) {
            $instance = $this->injector->get($class);
            if (method_exists($instance, $method = $request->attributes->get('method') ?: 'index')) {
                return array($instance, $method);
            }
        }

        return false;
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
    public function getArguments( Request $request, $controller )
    {
        $parameters = $this->getControllerParameters( $controller );
        $arguments = $this->instantiateParameters( $parameters );
        return $arguments;
    }

    /**
     * @param $controller
     * @return \ReflectionParameter[]
     */
    private function getControllerParameters($controller)
    {
        if (is_array($controller)) {
            $rm = (new \ReflectionMethod(get_class($controller[0]), $controller[1]));
            $parameters = $rm->getParameters();
            return $parameters;
        } else {
            $rf = new \ReflectionFunction($controller);
            $parameters = $rf->getParameters();
            return $parameters;
        }
    }

    /**
     * @param ReflectionParameter[] $parameters
     * @return array
     */
    private function instantiateParameters($parameters)
    {
        $arguments = [];
        foreach ($parameters as $parameter) {
            if ($parameter->getClass()) {
                $arguments[] = $this->injector->get($parameter->getClass());
            } else if ($parameter->getDefaultValue()) {
                $arguments[] = $parameter->getDefaultValue();
            } else {
                throw new \InvalidArgumentException('Unable to call method - ambiguous parameters');
            }

        }
        return $arguments;
    }
}