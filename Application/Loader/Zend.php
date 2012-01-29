<?php
namespace Application\Loader;
/**
 * An example Loader object.
 * All objects in this directory with the above namespace
 * are read in and instantiated by the Framework\Loader class, and
 * if they contain a load() method it is spl_autoload_registered.
 *
 * I'm not entirely convinced that this is a particularly smart way
 * to allow users to define their own autoloaders. It seems a tad overengineered
 * and all those class instantiations! Yuck :(
 */
class Zend {

    public function load($class) {
        $loader = \Framework\Loader::getInstance();
        if (strpos($class,'Zend_')===0) {
            include $loader->getAppPath().'Library/'.str_replace('_','/',$class).'.php';
        }
    }

}
