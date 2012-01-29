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
     * @var \Application\View
     */
    private $view;

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
        $this->model = new Data();
        $this->view->addScript('View/Header.phtml');
        $this->view['url'] = 'http://localhost/YetAnother/';
    }

    public function index() {
        $this->view['method'] = \Framework\Router::getInstance()->getMethod();
        $this->view['controller'] = \Framework\Router::getInstance()->getController();
        $this->view['params'] = implode(',',\Framework\Router::getInstance()->getParams());
        $this->view->addScript('View/Test.phtml');
    }

    /**
     * Destruct this Controller,
     * outputting a footer if the method ran.
     */
    public function __destruct() {
        $this->view->addScript('View/Footer.phtml');
        $this->view->render(true);
    }
}
