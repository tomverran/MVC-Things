<?php
namespace Controller;
use Library\View;

use Framework\Loader;
use Framework\Router;
/**
 * Testing web hooks! And again D:
 * An example controller. We don't extend any base classes here
 * for simplicity, but you'll probably want to create a base class
 * if you plan to output headers and such as we're doing here.
 */
class Index {


    /**
     * @var \Library\View
     */
    private $view;

    /**
     * Construct this test controller,
     * grabbing framework singletons and
     * outputting a header view.
     */
    public function __construct()
    {
        //initialisation
        $this->view = new View();
        $this->view->addScript('View/Header.phtml');
        $this->view['url'] = 'http://localhost/YetAnother/';
    }

    /**
     * Index methods are called when no other is supplied.
     */
    public function index()
    {
        $this->view['method'] = Router::getInstance()->getMethod();
        $this->view['controller'] = Router::getInstance()->getController();
        $this->view['params'] = implode(',',Router::getInstance()->getParams());
        $this->view->addScript('View/Test.phtml');
    }

    /**
     * Destruct this Controller,
     * outputting a footer if the method ran.
     */
    public function __destruct() {
        $this->view->addScript('View/Footer.phtml');
        $this->view->render(false);
    }
}
