<?php
namespace Framework;

/**
 * A Router class that tells the bootstrap what to execute
 * based on the URI the user has accessed the framework with
 */
class Router
{
    private static $instance;
    private $controller;
    private $method;

    protected function __construct()
    {
        $this->parse();
    }

    /**
     * Get an instance of our router
     * @return Router
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Router();
        }
        return self::$instance;
    }

    /**
     * Parse $_SERVER['REQUEST_URI'] extracting
     * controller and method routing information.
     */
    private function parse()
    {
        if ($pos = strpos($_SERVER['REQUEST_URI'],'?')!==false) {
            $urlParts = explode('/',substr($_SERVER['REQUEST_URI'],0,$pos));
        } else {
            $urlParts = explode('/',$_SERVER['REQUEST_URI']);
        }

        if (count($urlParts) > 2) {
            $this->method = self::camelCase(array_pop($trimmedParts),false);
            $this->controller = self::camelCase(array_pop($trimmedParts));
        }
    }

    /**
     * Convert a string to a CamelCase one.
     * @param $word the word(s) to convert over
     * @param bool $capital whether to capitalise the first letter (camelCase v CamelCase)
     * @return mixed the fixed string.
     * @static.
     */
    private static function camelCase($word, $capital=true)
    {
        $regex = $capital ? '/([^A-Za-z]|^)(\w)/' : '/[^A-Za-z](\w)/';
        return preg_replace_callback($regex,function($match) {
            return strtoupper(array_pop($match));
        }, $word);
    }

    /**
     * Get the controller
     * @return mixed
     */
    public function getController()
    {
        return $this->controller ? $this->controller : 'Index';
    }

    /**
     * Get the method
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method ? $this->method : 'index';
    }
}
