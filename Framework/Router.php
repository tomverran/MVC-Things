<?php
namespace Framework;

/**
 * A Router class that tells the bootstrap what to execute
 * based on the URI the user has accessed the framework with
 * @method static \Framework\Router getInstance();
 */
class Router extends Singleton {

    private $controller;
    private $method;
    private $params;

    protected function __construct() {
        parent::__construct();
        $this->parse();
    }

    /**
     * Parse $_SERVER['REQUEST_URI'] extracting
     * controller and method routing information.
     */
    private function parse() {

        $matches = array(); //get only arguments passed via slashes by diffing against SCRIPT_NAME
        preg_match('/'.preg_quote($_SERVER['SCRIPT_NAME'],'/').'(.*)/',$_SERVER['REQUEST_URI'],$matches);

        if (count($matches) > 1) {
            $trimmedParts = array_filter(explode('/',$matches[1]));
            $this->controller = self::camelCase(array_shift($trimmedParts));
            $this->method = self::camelCase(array_shift($trimmedParts),false);
            if (!$this->method) $this->method = 'index';
            $this->params = $trimmedParts;
        }
    }

    /**
     * Convert a string to a CamelCase one.
     * @param $word the word(s) to convert over
     * @param bool $capital whether to capitalise the first letter (camelCase v CamelCase)
     * @return mixed the fixed string.
     * @static.
     */
    private static function camelCase($word, $capital=true) {
        $regex = $capital ? '/([^A-Za-z]|^)(\w)/' : '/[^A-Za-z](\w)/';
        return preg_replace_callback($regex,function($match) {
            return strtoupper(array_pop($match)); //can this be done in pure regex?
        }, $word);
    }

    /**
     * Get the controller
     * @return mixed
     */
    public function getController() {
        return $this->controller;
    }

    /**
     * Get the method
     * @return mixed
     */
    public function getMethod() {
        return $this->method;
    }

    /**
     * Get the params
     * @return mixed
     */
    public function getParams() {
        return $this->params;
    }
}
