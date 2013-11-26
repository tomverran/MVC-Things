<?php
namespace Controller;
use Framework\Application;
use Framework\Router;
use Library\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @param \Library\View $view
     * @param \Framework\Application $app
     */
    public function __construct(View $view, Application $app, Request $request)
    {
        $this->view = $view;
        $this->view->addScript('Header.phtml');
        $this->view['url'] = $request->getBaseUrl();
    }

    /**
     * Index methods are called when no other is supplied.
     */
    public function index()
    {
        $this->view['method'] = 'no';
        $this->view['controller'] = 'no';
        $this->view['params'] = array('no');
        $this->view->addScript('Test.phtml');
        return new Response($this->view->render());
    }
}
