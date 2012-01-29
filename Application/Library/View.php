<?php
namespace Application\Library;

/**
 * A simple helper class to allow
 * the application's view to be built up
 * of several scripts sharing variables.
 */
class View implements \ArrayAccess {

    private $vars = array();
    private $scripts;

    /**
     * ArrayAccess get an offset
     * @param mixed $offset the offset to get
     * @return mixed its value.
     */
    public function offsetGet($offset) {
        return $this->vars[$offset];
    }

    /**
     * ArrayAccess set an offset
     * @param mixed $offset the offset to set
     * @param mixed $val its value to set it to
     */
    public function offsetSet($offset, $val) {
        $this->vars[$offset] = $val;
    }

    /**
     * ArrayAccess unset an offset
     * @param mixed $offset
     */
    public function offsetUnset($offset) {
        unset($this->vars[$offset]);
    }

    /**
     * ArrayAccess version of isset
     * @param mixed $offset The offset to check
     * @return bool whether it exists in the array
     */
    public function offsetExists($offset) {
        return isset($this->vars[$offset]);
    }

    /**
     * Add a script to the queue
     * @param $script the script to add
     * @param bool $prepend whether to prepend to the front
     */
    public function addScript($script, $prepend=false) {
        if (!$prepend) {
            $this->scripts[] = $script;
        } else {
            array_unshift($this->scripts,$script);
        }
    }

    /**
     * Render all the scripts queued in the view.
     * Uses Framework\Loader to handle including them.
     */
    public function render() {
        $loader = \Framework\Loader::getInstance();
        foreach ($this->scripts as $script) {
            $loader->loadScript($script,$this->vars);
        }
    }
}
