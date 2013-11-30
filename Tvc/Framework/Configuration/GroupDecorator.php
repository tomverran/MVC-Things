<?php
/**
 * GroupDecorator.php
 * @author Tom
 * @since 30/11/13
 */

namespace Framework\Configuration;


class GroupDecorator implements Configuration
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var string
     */
    private $defaultGroup;

    /**
     * @param Configuration $configuration
     * @param string $defaultGroup
     */
    public function __construct(Configuration $configuration, $defaultGroup)
    {
        $this->configuration = $configuration;
        $this->defaultGroup = $defaultGroup;
    }

    /**
     * Put a value into the configuration
     * @param string $key The key to save
     * @param string $value The value
     * @param $group
     * @return void
     */
    public function put($key, $value, $group = null)
    {
        $this->configuration->put($key, $value, $group ?: $this->defaultGroup);
    }

    /**
     * Get a value from the configuration
     * @param string $key The key
     * @param $group
     * @return string The value
     */
    public function get($key, $group = null)
    {
        return $this->configuration->get($key, $group ?: $this->defaultGroup);
    }
}