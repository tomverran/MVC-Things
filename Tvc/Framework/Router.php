<?php
namespace Framework;

/**
 * A Router class that tells the bootstrap what to execute
 * based on the URI the user has accessed the framework with
 */
class Router
{
    use Configurable;
    private static $instance;
    private $controller;
    private $method;
    private $params;

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
        $uri = 'http'. (isset($_SERVER['HTTPS']) ? 's' : null) .'://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $uri = str_replace($this->getConfig()->get('base_url'),'',$uri);

        if ($pos = strpos($uri,'?')!==false) {
            $urlParts = explode('/',substr($uri,0,$pos));
        } else {
            $urlParts = explode('/',$uri);
        }

        //remove any blank URL parts.
        $urlParts = array_filter($urlParts);

        if (!empty($urlParts)) {
            $this->controller = array_shift($urlParts);
        }

        if (!empty($urlParts)) {
            $this->method = array_shift($urlParts);
        }

        $this->params = $urlParts;
    }

    /**
     * Convert a string to a CamelCase one.
     * @param string $word the word(s) to convert over
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
        return $this->controller ?: $this->getConfig()->get('default_controller');
    }

    /**
     * Get the method
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method ?: $this->getConfig()->get('default_method');
    }

    /**
     * Get the parameters
     * @return array
     */
    public function getParams()
    {
        return $this->params ?: array();
    }
}
