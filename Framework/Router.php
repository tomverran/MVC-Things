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
    private $config;
    private $method;

    protected function __construct()
    {
        $this->config = new Config('router');
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
        $uri = 'http'. (isset($_SERVER['HTTPS']) ? 's' : null) .'://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $uri = str_replace($this->config->get('base_url'),'',$uri);

        if ($pos = strpos($uri,'?')!==false) {
            $urlParts = explode('/',substr($uri,0,$pos));
        } else {
            $urlParts = explode('/',$uri);
        }

        $vars = array('controller', 'method');
        foreach ($vars as $index=>$var) {
            if (isset($urlParts[$index])) {
                $this->$var = $urlParts[$index];
            } else {
                break;
            }
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
