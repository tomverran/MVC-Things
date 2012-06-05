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
    public static $namespace;
    public static $baseDir;
    public static $appDir;
    public static $libDir;

    /**
     * Construct our loader.
     */
    protected function __construct()
    {
        $p = DIRECTORY_SEPARATOR;
        self::$baseDir = dirname(__FILE__)."$p..$p";
        self::$appDir = self::$baseDir."Application$p";
        self::$libDir = self::$appDir."Library$p";

        set_include_path(get_include_path().
            PATH_SEPARATOR.self::$libDir.
            PATH_SEPARATOR.self::$appDir);

        spl_autoload_extensions('.php');
        spl_autoload_register(); //handle default loading of namespaces
        spl_autoload_register(array($this,'zend'));
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
            if (file_exists(self::$libDir.$name) && include $name) {
                return true;
            }
        }
        return false;
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
        include self::$appDir.$script;
    }
}
