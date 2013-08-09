<?php
/**
 * Compile.php
 * @author Tom
 * @since 09/08/13
 */

namespace Library\DI\Build;


class Compiler {

    /**
     * Compile many files
     */
    public function compileAll()
    {
        $dir = dirname(__FILE__).'/../Config/';
        $outDir = dirname(__FILE__).'/../Container/';

        foreach (scandir($dir) as $file) {
            if (strpos($file, '.php') !== false) {
                $this->compile($dir, $outDir, $file);
            }
        }
    }

    /**
     * Compile a file
     * @param $inDir
     * @param $outDir
     * @param $file
     */
    public function compile($inDir, $outDir, $file)
    {
        $classTemplate = file_get_contents(dirname(__FILE__).'/Class.txt');
        $functionTemplate = file_get_contents(dirname(__FILE__).'/Function.txt');

        //Figure out our classname, begin output
        $className = str_replace('.php', '', $file);


        //get classes to DI
        $matches = array();
        $content = file_get_contents($inDir.$file);
        preg_match_all('/use (\S+) as ([^\s;]+)/', $content, $matches);

        //compiled function strings
        $functions = array();

        //create static methods for each class we're containing
        foreach (array_combine($matches[1], $matches[2]) as $namespace=>$class) {

            //create a reflection class.
            $rc = new \ReflectionClass($namespace);
            if ($rc->isAbstract()) {
                continue;
            }

            //grab the constructor method
            $constructor = $rc->getConstructor();
            if ($constructor && !$constructor->isPublic()) {
                continue;
            }

            //find out the parameters the constructor has, if it exists
            $params = $constructor ? $constructor->getParameters() : array();

            $paramArray = array();
            foreach ($params as $param) {
                $paramArray[] = '$'.$param->name;
            }

            $functions[] = str_replace(array('{NAMESPACE}', '{CLASS}', '{PARAMETERS}'), array($namespace, $class, implode(',',$paramArray)), $functionTemplate);
        }

        $finalOutput = str_replace(array('{CLASSNAME}', '{FUNCTIONS}'), array($className, implode("\n", $functions)), $classTemplate);
        file_put_contents($outDir.$file, $finalOutput);
    }

}