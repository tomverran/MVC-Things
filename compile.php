<?php
/**
 * A modified version of the phpioc compile script,
 * just with the path names for this framework
 * compile.php
 * @author Tom
 * @since 10/08/13
 */

$basePath = dirname(__FILE__);

//Include the composer autoloader file from the project's vendor dir
require($basePath . '/vendor/autoload.php');

$compiler = new Carefulcoder\Ioc\Compiler();
$compiler->compileAll($basePath.'/Application/Library/Ioc');

