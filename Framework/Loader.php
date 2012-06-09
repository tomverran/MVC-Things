<?php
namespace Framework;

/**
 * The loader class that is basically the core of this "framework"
 * it handles loading namespaced classes and Zend style (underscored) classes
 */
class Loader
{
    private static $instance;
    private $baseDir;
    private $appDir;
    private $libDir;

    /**
     * Construct our loader.
     * @param string $dir The base directory
     */
    protected function __construct($dir)
    {
        $p = DIRECTORY_SEPARATOR;
        $this->baseDir = $dir.$p;
        $this->appDir = $this->baseDir."Application$p";
        $this->libDir = $this->appDir."Library$p";

        set_include_path(get_include_path().
            PATH_SEPARATOR.$this->libDir.
            PATH_SEPARATOR.$this->appDir);

        spl_autoload_extensions('.php');
        spl_autoload_register(); //handle default loading of namespaces
        spl_autoload_register(array($this,'zend'));
    }

    /**
     * Initialise class loading.
     * Called once, by the bootstrap
     * @static
     * @param string $dir The base directory
     * @throws \RuntimeException
     */
    public static function init($dir)
    {
        if (!self::$instance) {
            self::$instance = new Loader($dir);
        } else {
            throw new \RuntimeException('Loader already initialised');
        }
    }

    /**
     * Get the instance of our loader
     * @static
     * @return Loader
     * @throws \UnexpectedValueException if not loaded
     */
    public static function getInstance()
    {
        if (isset(self::$instance)) {
            return self::$instance;
        } else {
            throw new \UnexpectedValueException('Loader not initialised.');
        }
    }

    /**
     * Auto load zend style
     * @param $class
     * @return bool
     */
    private function zend($class)
    {
        if (strpos($class,'_')!==false) {
            $name = str_replace('_',DIRECTORY_SEPARATOR,$class).'.php';
            if (file_exists($this->libDir.$name) && include $name) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get the base directory
     * @return string
     */
    public function getBaseDirectory()
    {
        return $this->baseDir;
    }
}
