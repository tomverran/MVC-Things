<?php
namespace Framework;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * A Router class that tells the bootstrap what to execute
 * based on the URI the user has accessed the framework with
 */
class Router implements EventSubscriberInterface
{

    /**
     * Parse the request URL extracting
     * controller and method routing information.
     */
    public function parse(GetResponseEvent $event)
    {
        $req = $event->getRequest();
        $uri = str_replace(array($req->getBaseUrl(), '?' . $req->getQueryString()), '',$req->getRequestUri());
        $urlParts = preg_split('#/#', $uri, -1, PREG_SPLIT_NO_EMPTY);

        //grab our controller, args and method from the URL or set them to '' if empty
        list($controller, $method) = ($parts = array_replace(array('',''), $urlParts));
        $req->attributes->set('args', array_slice($parts,2));
        $req->attributes->set('controller', $controller);
        $req->attributes->set('method', $method);

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
