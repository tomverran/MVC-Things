<?php
namespace Framework;

/**
 * A Router class that tells the bootstrap what to execute
 * based on the URI the user has accessed the framework with
 * @method static \Framework\Router getInstance();
 */
class Router extends Singleton {

    private $uri;
    private $bootstrap = 'index.php';
    private $controller;
    private $appPath;
    private $method;
    private $params;

    /**
     * Set the name of our MVC bootstrap file so we can
     * ignore all directories and such prior to it.
     * @param $bootstrap
     */
    public function setBootstrapName($bootstrap) {
        $this->bootstrap = $bootstrap;
    }

    /**
     * Set a URI to parse.
     * @param $uri
     */
    public function setUri($uri) {
        $this->uri = $uri;
        $this->parse($uri);
    }

    /**
     * Parse a URI, extracting a controller, method and params
     * @param $uri the URI to parse.
     */
    private function parse($uri) {

        //strip out GET arguments
        $uri = array_shift(explode('?',$uri));

        //find position of bootstrap and ignore all before
        $parts = array_filter(explode('/',$uri));
        $pos = array_search('index.php',$parts);

        //get and format to camelcase the controller
        if ($pos) {
            $trimmedParts = array_slice($parts,$pos);
            $this->appPath = implode('/',array_slice($parts,0,$pos)).'/';
            if (count($trimmedParts))$this->controller = self::camelCase(array_shift($trimmedParts));
            if (count($trimmedParts)) $this->method = self::camelCase(array_shift($trimmedParts),false);
            $this->params = $trimmedParts;
        }
    }

    /**
     * Convert an arbitary string to a CamelCase one.
     * @static.
     * @param $word the word(s) to convert over
     * @param bool $capital whether to capitalise the first letter (camelCase v CamelCase)
     * @return mixed the fixed string.
     */
    private static function camelCase($word, $capital=true) {
        $regex = $capital ? '/([^A-Za-z]|^)(\w)/' : '/[^A-Za-z](\w)/';
        return preg_replace_callback($regex,function($match) {
            return strtoupper(array_pop($match)); //can this be done in pure regex?
        }, $word);
    }

    /**
     * Get the base URL.
     * @return mixed
     */
    public function getBaseUrl() {
        return $this->appPath;
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
