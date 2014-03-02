<?php
/**
 * Configuration.php
 * @author Tom
 * @since 30/11/13
 */

namespace Framework\Configuration;


use Framework\Configuration\ConfigurationIni;
use Framework\Configuration\GroupDecorator;
use Framework\Event\BindClassesEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class Subscriber implements EventSubscriberInterface
{

    /**
     * Make a configuration object available
     * @param BindClassesEvent $e
     */
    public function bindConfiguration(BindClassesEvent $e)
    {
        //create a configuration object which stores stuff grouped by classnames
        $configuration = new ConfigurationIni(dirname(__FILE__).'/../../Application/Config', 'Production');

        //return a decorated object with a default class name
        $e->getInjector()->bind(function($class, $for) use(&$configuration) {
            return new GroupDecorator($configuration, str_replace('\\', '_', $for));
        }, 'Framework\Configuration\Configuration');

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
        return array('tvc.bind' => 'bindConfiguration');
    }
}