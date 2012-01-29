<?php
namespace Application\Controller;
use Application\Model\Data;
use Framework\Loader;

/**
 * An example controller. We don't extend any base classes here
 * for simplicity, but you'll probably want to create a base class
 * if you plan to output headers and such as we're doing here.
 */
class Test {

    /**
     * @var \Framework\Loader
     */
    private $loader;

    /**
     * @var \Application\Model\Data
     */
    private $model;

    /**
     * Construct this test controller,
     * grabbing framework singletons and
     * outputting a header view.
     */
    public function __construct() {

        //initialisation
        $this->loader = Loader::getInstance();
        $this->model = new Data();
        ob_start();

        //header viewscript.
        $this->loader->loadScript('View/Header.phtml');
    }

    /**
     * Action method that
     * is automatically invoked.
     */
    public function hello() {
        $data = $this->model->getData();
        $this->loader->loadScript('View/Test.phtml',$data,'htmlentities');
        $form = new \Zend_Form();
    }

    /**
     * Destruct this Controller,
     * outputting a footer if the method ran.
     */
    public function __destruct() {
        $this->loader->loadScript('View/Footer.phtml');
        if (isset($GLOBALS['ran'])) { //hack? don't show headers if 404
            echo ob_get_clean();
        } else {
            ob_end_clean();
        }
    }
}
