<?php
/**
 * BindClassesEvent.php
 * @author Tom
 * @since 30/11/13
 */

namespace Framework\Event;
use Symfony\Component\EventDispatcher\Event;
use tomverran\di\Injector;

class BindClassesEvent extends Event
{
    private $injector;

    public function __construct( Injector $injector )
    {
        $this->injector = $injector;
    }

    public function getInjector()
    {
        return $this->injector;
    }
} 