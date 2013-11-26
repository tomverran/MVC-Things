<?php
namespace Framework;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * A Router class that tells the bootstrap what to execute
 * based on the URI the user has accessed the framework with
 */
class Router implements EventSubscriberInterface
{
    use Configurable;

    /**
     * Parse $_SERVER['REQUEST_URI'] extracting
     * controller and method routing information.
     */
    public function parse(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $uri = $request->getSchemeAndHttpHost() . $request->getRequestUri();
        $uri = str_replace($this->getConfig()->get('base_url'),'',$uri);

        if ($pos = strpos($uri,'?')!==false) {
            $urlParts = explode('/',substr($uri,0,$pos));
        } else {
            $urlParts = explode('/',$uri);
        }

        //remove any blank URL parts.
        list($controller, $method) = array_values(array_filter($urlParts));
        $request->attributes->set('controller', $controller);
        $request->attributes->set('method', $method);
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array('kernel.request' => 'parse');
    }
}
