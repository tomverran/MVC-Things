<?php
namespace Framework;
use Controller\Index;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * A Router class that tells the bootstrap what to execute
 * based on the URI the user has accessed the framework with
 */
class Router
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var string
     */
    private $controller;

    /**
     * @var string
     */
    private $method;

    /**
     * @param Request $r
     */
    public function __construct( Request $r )
    {
        $this->request = $r;
        $this->parse();
    }
    /**
     * Parse the request URL extracting
     * controller and method routing information.
     */
    public function parse()
    {
        $req = $this->request;
        $uri = str_replace([$req->getBaseUrl(), '?' . $req->getQueryString()], '', $req->getRequestUri() );
        $urlParts = preg_split( '#/#', $uri, -1, PREG_SPLIT_NO_EMPTY );

        //grab our controller, args and method from the URL or set them to '' if empty
        list( $this->controller, $this->method ) = ( $parts = array_replace( ['',''], $urlParts ) );


    }

    public function getController()
    {
        if ( !$this->controller ) {
            return Index::class;
        }
        return 'Controller\\' . ucfirst( $this->controller);
    }

    public function getMethod()
    {
        if ( !$this->method ) {
            return 'index';
        }
        return $this->method;
    }
}
