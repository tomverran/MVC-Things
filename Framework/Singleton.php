<?php
namespace Framework;
/**
 * Basic singleton class so we don't have to have global
 * classes or anything ugly like that. Since we can't know what type of object getInstance returns
 * classes extending Singleton should probably use the method docblock for code completion/.
 */
abstract class Singleton {

    private static $instances = array();
    protected function __construct(){}

    /**
     * Get an instance of ourselves
     * @static and very reliant on late static binding :)
     * @return mixed
     */
    public static function getInstance() {
        $class = get_called_class();
        if (isset(self::$instances[$class])) {
            return self::$instances[$class];
        } else {
            self::$instances[$class] = new $class();
            return self::$instances[$class];
        }
    }
}
