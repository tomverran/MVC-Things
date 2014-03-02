<?php
/**
 * ConfigurationArray.php
 * @author Tom
 * @since 02/03/14
 */

namespace Framework\Configuration;

/**
 * Class ConfigurationArray - A configuration object that uses an array
 * @package Framework\Configuration
 */
class ConfigurationArray implements Configuration
{
    /**
     * @var array
     */
    private $settings;

    /**
     * Construct this array config class
     * @param $settings
     */
    public function __construct($settings)
    {
        $this->settings = $settings;
    }

    /**
     * Put a value into the configuration
     * @param string $key The key to save
     * @param string $value The value
     * @param null $group
     * @return void
     */
    public function put($key, $value, $group = null)
    {
        $this->settings[$key] = $value;
    }

    /**
     * Get a value from the configuration
     * @param string $key The key
     * @param null $group
     * @return string The value
     */
    public function get($key, $group = null)
    {
        return isset( $this->settings[$key] ) ? $this->settings[$key] : null;
    }
}