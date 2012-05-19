<?php
namespace Application\Controller;
use Application\Model\Repository\EngineRepository;
use Application\Model\Repository\CarRepository;
use Application\Model\Repository\Repository;
use Application\Library\View;
use Framework\Loader;
use Framework\Router;
/**
 * Testing web hooks! And again D:
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
     * @var \Application\Model\Repository\CarRepository
     */
    private $cars;

    /**
     * Construct this test controller,
     * grabbing framework singletons and
     * outputting a header view.
     */
    public function __construct()
    {
        //initialisation
        $this->view = new View();
        $this->cars = new CarRepository(new EngineRepository());
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
     * Don't mind me, just playing about with MVC things.
     */
    public function cars()
    {
        $car = $this->cars->get(1, Repository::EAGER);
        var_dump($car);
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
