<?php
namespace Framework;

/**
 * Default implementation of a config class. This one loads in PHP files
 * based on the include path, assuming a directory called Configuration under the include path.
 * @author Tom
 * @since 09/06/12
 */
class Config {

    /**
     * @var array
     */
    private $config;

    /**
     * @param $file
     */
    public function __construct($file)
    {
        $config = array();
        ob_start();
        include 'Configuration/'.$file.'.php';
        ob_end_clean();
        $this->config = $config;
    }

    /**
     * @param $key
     * @param null $default
     * @return null
     * @throws \InvalidArgumentException
     */
    public function get($key, $default=null)
    {
        if (isset($this->config[$key])) {
            return $this->config[$key];
        } else if ($default) {
            return $default;
        } else {
            throw new \InvalidArgumentException('Bad Key');
        }
    }
}
