<?php
/**
 * ConfigurationIni.php
 * @author Tom
 * @since 27/08/13
 */

namespace Framework\Configuration;

/**
 * A Configuration object that writes to and reads from ini files with a dubious packagist dependency.
 * @package Framework\Configuration
 */
class ConfigurationIni implements Configuration
{
    /**
     * @var array Pooled configuration objects
     * To prevent us repeatedly reading the same config file
     */
    private static $configObjects = [];

    /**
     * @var string Group for each keypair to be under
     */
    public $group;

    /**
     * @var \ezcConfiguration
     * The config for this particular instance
     */
    private $config;

    /**
     * @var string The directory of the config file
     */
    private $dir;

    /**
     * @var string The filename of the config file
     */
    private $file;

    /**
     * Construct a new ConfigurationIni object
     * @param string $dir The directory in which the ini file is located
     * @param string $file The name of the file itself, excluding its extension
     */
    public function __construct($dir, $file)
    {
        if (!isset(self::$configObjects[$dir.$file])) {

            $config = new \ezcConfigurationIniReader();
            $config->init($dir, $file );

            self::$configObjects[$dir.$file] = $config->load();
        }

        $this->config = &self::$configObjects[$dir.$file];
        $this->file = $file;
        $this->dir = $dir;
    }

    /**
     * Put a value into a group / key pair.
     * @param string $key
     * @param string $value
     */
    public function put($key, $value)
    {
        $this->config->setSetting($this->group, $key, $value);

        //write our changes to disk.
        $writer = new \ezcConfigurationIniWriter();
        $writer->init($this->dir, $this->file, $this->config);
        $writer->save();
    }

    /**
     * Get a value from a group / key pair.
     * @param string $key
     * @return string
     */
    public function get($key)
    {
        return $this->config->getStringSetting($this->group, $key);
    }

    /**
     * Set a group for all subsequent reads and writes so multiple things can use the same keys
     * @param string $group
     * @return void
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }
}