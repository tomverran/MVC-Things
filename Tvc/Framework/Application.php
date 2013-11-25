<?php
/**
 * Application.php
 * @author Tom
 * @since 25/11/13
 */

namespace Framework;


/**
 * Slightly confusing since this is in Framework but represents a running instance of our application
 * Basically provides hooks to fire on given application life cycle events, e.g. success
 * Class Application
 * @package Framework
 */
class Application
{

    const SUCCESS = 'afterSuccess';

    const NOT_FOUND = 'onNotFound';

    /**
     * @var array
     */
    protected $hooks = array();

    /**
     * @var array All hooks we've fired
     */
    protected $fired = array();

    /**
     * Hook into an event
     * @param string $event The event to hook into
     * @param callable $callback
     */
    public function on($event, callable $callback)
    {
        if (!isset($this->hooks[$event])) {
            $this->hooks[$event] = array();
        }
        $this->hooks[$event][] = $callback;
    }

    /**
     * Fire an event
     * @param $event
     */
    public function fire($event)
    {
        foreach ($this->hooks[$event] as $callable) {
            $callable(); //run the event handlers
        }
        $this->fired[] = $event;
    }

    /**
     * Have we fired a given event?
     * @param $event
     * @return bool
     */
    public function fired($event)
    {
        return in_array($event, $this->fired);
    }
} 