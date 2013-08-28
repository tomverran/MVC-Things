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
     * Set a group for all subsequent reads and writes so multiple things can use the same keys
     * @param string $group
     * @return void
     */
    public function setGroup($group);

    /**
     * Put a value into the configuration
     * @param string $key The key to save
     * @param string $value The value
     * @return void
     */
    public function put($key, $value);

    /**
     * Get a value from the configuration
     * @param string $key The key
     * @return string The value
     */
    public function get($key);
}