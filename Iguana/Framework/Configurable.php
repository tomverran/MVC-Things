<?php
/**
 * Configurable.php
 * @author Tom
 * @since 27/08/13
 */

namespace Framework;

use Framework\Configuration\ConfigurationIni;

/**
 * Dubious-o-matic trait that lets any class be configurable
 * With a basic ini config adapter which groups keys by classname
 * @package Framework
 */
trait Configurable {

    /**
     * @var ConfigurationIni
     */
    private static $_configurable_config;

    /**
     * The group to use in our config file
     * @return mixed
     */
    private static function configurableGroup()
    {
        return str_replace('\\', '_', get_called_class());
    }
    /**
     * Set up our config backend.
     * In this case we use one ini file per class.
     */
    private static function configurableInitConfig()
    {
        if (!self::$_configurable_config) {
            self::$_configurable_config = new ConfigurationIni(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..', 'Config');
        }
    }

    /**
     * Save a key value. Given that this class is meant only for application configuration
     * this should only ever really be called on installation as it makes no attempt at efficiency
     * @param string $key
     * @param mixed $value
     */
    public static function put($key, $value)
    {
        self::configurableInitConfig();
        self::$_configurable_config->put(self::configurableGroup(), $key, $value);
    }

    /**
     * Get a key value
     * @param $key
     * @return bool
     */
    public static function get($key)
    {
        self::configurableInitConfig();
        return self::$_configurable_config->get(self::configurableGroup(), $key);
    }
}