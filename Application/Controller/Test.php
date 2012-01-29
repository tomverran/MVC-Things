<?php
namespace Application\Controller;
use Application\Library\View;
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
        $this->view = new View();
        $this->loader = Loader::getInstance();
        $this->model = new Data();

        //header viewscript.
        $this->view->addScript('View/Header.phtml');
    }

    /**
     * Action method that
     * is automatically invoked.
     */
    public function hello() {
        $this->view['message'] = array_pop($this->model->getData());
        $this->view->addScript('View/Test.phtml');
    }

    /**
     * Destruct this Controller,
     * outputting a footer if the method ran.
     */
    public function __destruct() {
        $this->view->addScript('View/Footer.phtml');
        if (isset($GLOBALS['ran'])) { //hack? don't show headers if 404
            $this->view->render();
        }
    }
}
