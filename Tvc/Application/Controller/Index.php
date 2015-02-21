<?php
namespace Controller;
use Framework\Application;
use Framework\Router;
use Library\View;
use Model\Test;
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
     * Index methods are called when no other is supplied.
     * @param View $view
     * @param Request $r
     * @return Response
     */
    public function index( View $view, Request $r )
    {
        $view['method'] = 'index';
        $view['url'] = $r->getBaseUrl();
        $view['controller'] = get_called_class();
        $view['params'] = $r->getQueryString();

        $view->addScript( 'Header.phtml' );
        $view->addScript( 'Test.phtml' );
        $view->addScript( 'Footer.phtml' );
        return new Response( $view->render() );
    }

    public function test( Response $r )
    {
        $r->setContent( "hi world" );
        return $r;
    }
}
