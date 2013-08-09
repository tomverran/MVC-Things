<?php
namespace Controller;
use Framework\Router;
use Library\View;
use Library\DI;

/**
 * An example controller. We don't extend any base classes here
 * for simplicity, but you'll probably want to create a base class
 * if you plan to output headers and such as we're doing here.
 */
class Index
{
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
        $this->view = new View();
        $this->view->addScript('Header.phtml');
        $this->view['url'] = 'http://localhost/MVC-Things/';
    }

    /**
     * Index methods are called when no other is supplied.
     */
    public function index()
    {
        $compiler = new DI\Build\Compiler();
        $compiler->compileAll();


        $this->view['method'] = Router::getInstance()->getMethod();
        $this->view['controller'] = Router::getInstance()->getController();
        $this->view->addScript('Test.phtml');
    }

    /**
     * Destruct this Controller,
     * outputting a footer.
     */
    public function __destruct()
    {
        $this->view->addScript('Footer.phtml');
        $this->view->render();
    }
}
