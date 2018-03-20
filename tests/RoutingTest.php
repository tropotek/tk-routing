<?php
namespace tests;

use Tk\Request;
use Tk\Routing\Route;
use Tk\Routing\RouteCollection;
use Tk\Routing\UrlMatcher;


/**
 * Class DispatcherTest
 *
 * @author Michael Mifsud <info@tropotek.com>
 * @see http://www.tropotek.com/
 * @license Copyright 2016 Michael Mifsud
 */
class RoutingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var 
     */
    protected $routeCollection = null;
    
    
    public function __construct()
    {
        parent::__construct('Routing Test');
        
        
    }

    public function setUp()
    {

        $this->routeCollection = new RouteCollection();

        $this->routeCollection->add('Home', new Route(
            '/index.html',
            'App\Controller\Index::doDefault',
            array('param1' => 'value1')     // Params that can be sent to the controller...
        ));
        $this->routeCollection->add('Contact', new Route(
            '/contact.html',
            'App\Controller\Index::doDefault',
            array('param1' => 'value1')     // Params that can be sent to the controller...
        ));
        
        $this->routeCollection->add('HomeNew', new Route(
            '/home',
            'App\Controller\Index::doDefault',
            array('param1' => 'value1')     // Params that can be sent to the controller...
        ));
        $this->routeCollection->add('ContactNew', new Route(
            '/contact',
            'App\Controller\Index::doDefault',
            array('param1' => 'value1')     // Params that can be sent to the controller...
        ));
        
    }

    public function tearDown()
    {

    }



    public function testRoutes()
    {
        $this->assertInstanceOf(\Tk\Routing\RouteCollection::class, $this->routeCollection);
        
        $route = $this->routeCollection->get('Home');
        $this->assertInstanceOf(\Tk\Routing\Route::class, $route);
    }


    public function testRouteMatcher()
    {
        $matcher = new UrlMatcher($this->routeCollection);

        $request = new Request(\Tk\Uri::create('/index.html'), 'GET', \Tk\Headers::create(), $_REQUEST, $_SERVER);
        $route = $matcher->match($request);
        $this->assertEquals('Home', $route['_route']);
        $this->assertEquals('App\Controller\Index::doDefault', $route['_controller']);

        $request = new Request(\Tk\Uri::create('/home'), 'GET', \Tk\Headers::create(), $_REQUEST, $_SERVER);
        $route = $matcher->match($request);
        $this->assertEquals('HomeNew', $route['_route']);
        $this->assertEquals('App\Controller\Index::doDefault', $route['_controller']);

        $request = new Request(\Tk\Uri::create('/contact')->set('test', 'value'), 'GET', \Tk\Headers::create(), $_REQUEST, $_SERVER);
        $route = $matcher->match($request);
        $this->assertEquals('ContactNew', $route['_route']);
        $this->assertEquals('App\Controller\Index::doDefault', $route['_controller']);

    }


}

