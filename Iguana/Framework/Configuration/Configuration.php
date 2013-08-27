<?php
/**
 * Configuration.php
 * @author Tom
 * @since 27/08/13
 */
namespace Framework\Configuration;

/**
 * An interface defining a configuration object
 * @package Framework\Configuration
 */
interface Configuration
{
    /**
     * Put a value into the configuration
     * @param string $group Group to put the key / value into
     * @param string $key The key to save
     * @param string $value The value
     * @return void
     */
    public function put($group, $key, $value);

    /**
     * Get a value from the configuration
     * @param string $group The group
     * @param string $key The key
     * @return string The value
     */
    public function get($group, $key);
}