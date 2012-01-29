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
    const APP_DIR = 'Application';

    /**
     * Construct our loader.
     */
    protected function __construct() {
        spl_autoload_extensions('.php');
        spl_autoload_register(); //handle default autoloading of namespaces
        $this->setBasePath(dirname(__FILE__).'/../');
    }

    /**
     * Handle loading in user-defined classes which
     * contain a load() function to autoload third party libs.
     */
    private function scanLoaders() {

        if (!isset($this->path)) throw new \Exception('No Path Defined');
        $loaderDirectory = $this->path.Loader::APP_DIR.'/Loader/';

        if (is_dir($loaderDirectory)) {
            foreach (scandir($loaderDirectory) as $file) {
                if (is_file($loaderDirectory.$file)) {

                    //convert the path to our loader class into a nice namespace
                    $namespaced = '\\'.Loader::APP_DIR.'\Loader\\'.substr($file,0,-4);

                    //if it exists add to stach
                    if (class_exists($namespaced)) {
                        $object = new $namespaced();
                        if (method_exists($object,'load')) {
                            spl_autoload_register(array($object,'load'));
                        }
                    }
                }
            }
        }
    }

    /**
     * Set the path to the Application/ folder,
     * to know where to find Scripts and such.
     * @param string $path the absolute path.
     */
    private function setBasePath($path) {
        if (!is_dir($path)) throw new Exception('Bad Path');
        $this->path = $path;
        $this->scanLoaders();
    }

    /**
     * Get the Base Path
     * @return string
     */
    public function getBasePath() {
        return $this->path;
    }

    /**
     * Get the absolute application path.
     * @return string
     */
    public function getAppPath() {
        return $this->path.Loader::APP_DIR.'/';
    }

    /**
     * Load a procedural Script.
     * @param string $script the script name
     * @param array $args arguments to supply
     * @param string|array|null $function a function to apply to all arg values.
     */
    public function loadScript($script, $args=array(), $function=null) {

        //handle filtering the given variables with a callback
        if ($function) {
            foreach ($args as $k=>$v) {
                $args[$k] = call_user_func($function,$v);
            }
        }

        extract($args);
        include $this->path.Loader::APP_DIR.'/'.$script;
    }
}
