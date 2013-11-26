<?php
namespace Library;

/**
 * A simple helper class to allow
 * the application's view to be built up
 * of several scripts sharing variables.
 */
class View implements \ArrayAccess
{
    /**
     * @var array The vars our views can access
     */
    private $vars = array();

    /**
     * @var array of script file names
     */
    private $scripts = array();

    /**
     * Escape a string or array
     * @param string|array $input
     * @return string|array
     */
    public function escape($input)
    {
        if (is_array($input)) {
            $output = array();
            foreach ($input as $key=>$item) {
                $output[$key] = $this->escape($item);
            }
            return $output;
        } else {
            return htmlentities($input, ENT_QUOTES, 'UTF-8');
        }
    }

    /**
     * ArrayAccess get an offset
     * @param mixed $offset the offset to get
     * @return mixed its value.
     */
    public function offsetGet($offset)
    {
        return $this->vars[$offset];
    }

    /**
     * ArrayAccess set an offset
     * @param mixed $offset the offset to set
     * @param mixed $val its value to set it to
     */
    public function offsetSet($offset, $val)
    {
        $this->vars[$offset] = $val;
    }

    /**
     * ArrayAccess unset an offset
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->vars[$offset]);
    }

    /**
     * ArrayAccess version of isset
     * @param mixed $offset The offset to check
     * @return bool whether it exists in the array
     */
    public function offsetExists($offset)
    {
        return isset($this->vars[$offset]);
    }

    /**
     * Add a script to the queue
     * @param string $script the script to add
     * @param bool $prepend whether to prepend to the front
     */
    public function addScript($script, $prepend=false)
    {
        if (!$prepend) {
            $this->scripts[] = $script;
        } else {
            array_unshift($this->scripts,$script);
        }
    }


    /**
     * Render all of our scripts
     * in the order which they were supplied
     * @return string
     */
    public function render()
    {
        ob_start();
        foreach ($this->scripts as $script) {
            include 'Application' . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR . $script;
        }
        return ob_get_clean();
    }
}
