<?php
namespace Framework;
include('Singleton.php');
/**
 * The loader class that is basically the core of this "framework"
 * it handles autoloading namespaces, as you would, and also
 * reading Application/Loader to let you load additional libs.
 * @method static \Framework\Loader getInstance();
 */
class Loader extends Singleton
{
    private $path = '.';
    const APP_DIR = 'Application/';
    const LIB_DIR = 'Application/Library/';

    /**
     * Construct our loader.
     */
    protected function __construct() {
        spl_autoload_extensions('.php');
        spl_autoload_register(); //handle default loading of namespaces
        set_include_path(get_include_path().PATH_SEPARATOR.self::LIB_DIR);
        spl_autoload_register(array($this,'zend'));
        $this->setBasePath(dirname(__FILE__).'/../');
    }

    /**
     * Auto load zend style
     * @param $class
     * @return bool
     */
    private function zend($class)
    {
        if (strpos($class,'_')!==false) {
            $name = self::LIB_DIR.str_replace('_','/',$class).'.php';
            if (file_exists($name)) {
                include $name;
                return (class_exists($class));
            }
        }
        return false;
    }

    /**
     * Set the path to the Application/ folder,
     * to know where to find Scripts and such.
     * @param string $path the absolute path.
     */
    private function setBasePath($path) {
        if (!is_dir($path)) throw new \Exception('Bad Path');
        $this->path = $path;
    }

    /**
     * Get the Base Path
     * @return string
     */
    public function getBasePath() {
        return $this->path;
    }

    /**
     * Load a procedural Script.
     * @param string $script the script name
     * @param array $args arguments to supply
     * @param string|array|null $function a function to apply to all arg values.
     */
    public function loadScript($script, $args=array(), $function=null) {

        //handle filtering the given variables with a callback
        if (is_callable($function)) {
            foreach ($args as $k=>$v) {
                $args[$k] = call_user_func($function,$v);
            }
        }

        extract($args);
        include $this->path.Loader::APP_DIR.$script;
    }
}
