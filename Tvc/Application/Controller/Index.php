<?php
namespace Controller;
use Framework\Application;
use Framework\Router;
use Library\View;

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
    public function __construct(View $view, Application $app)
    {
        $this->view = $view;
        $this->view->addScript('Header.phtml');
        $this->view['url'] = Router::getInstance()->getConfig()->get('base_url');

        //just an example of interacting with application events
        $app->on(Application::SUCCESS, (function() use ($view) {
            $view->addScript('Footer.phtml');
            $view->render();
        }));
    }

    /**
     * Index methods are called when no other is supplied.
     */
    public function index()
    {
        $this->view['method'] = Router::getInstance()->getMethod();
        $this->view['controller'] = Router::getInstance()->getController();
        $this->view['params'] = Router::getInstance()->getParams();
        $this->view->addScript('Test.phtml');
    }
}
