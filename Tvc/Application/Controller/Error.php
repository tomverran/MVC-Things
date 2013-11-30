<?php
/**
 * Error.php
 * @author Tom
 * @since 30/11/13
 */

namespace Controller;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Error implements EventSubscriberInterface
{

    /**
     * Respond to an exception
     * @param GetResponseForExceptionEvent $e
     */
    public function handleException( GetResponseForExceptionEvent $e )
    {
        if ($e->getException() instanceof NotFoundHttpException) {
            $e->setResponse(new Response('Not Found'));
        }
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
     */
    public static function getSubscribedEvents()
    {
        return array('kernel.exception' => 'handleException');
    }
}