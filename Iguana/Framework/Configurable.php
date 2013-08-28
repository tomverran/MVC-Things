<?php
/**
 * Configurable.php
 * @author Tom
 * @since 27/08/13
 */

namespace Framework;

use Framework\Configuration\ConfigurationIni;

/**
 * Dubious-o-matic trait that lets any object be configurable
 * With a basic ini config adapter which groups keys by classname
 * @package Framework
 */
trait Configurable {

    /**
     * @var ConfigurationIni
     */
    private $config;

    /**
     * Get our configuration object
     * @return \Framework\Configuration\Configuration
     */
    public function getConfig()
    {
        if (!$this->config) {
            $this->config = new ConfigurationIni(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..', 'Config');
            $this->config->setGroup(str_replace('\\', '_', get_called_class()));
        }
        return $this->config;
    }
}