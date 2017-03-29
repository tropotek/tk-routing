<?php
namespace Tk\Listener;

use Tk\Event\RequestEvent;
use Tk\Routing\MatcherInterface;
use Tk\Event\Subscriber;


/**
 * Class RouteListener
 *
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2016 Michael Mifsud
 */
class RouteListener implements Subscriber
{
    /**
     * @var MatcherInterface
     */
    protected $matcher = null;

    
    
    /**
     * 
     * @param MatcherInterface $matcher
     */
    public function __construct(MatcherInterface $matcher)
    {
        $this->matcher = $matcher;
    }


    /**
     * 
     * @param RequestEvent $event
     */
    public function onRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        if ($request->hasAttribute('_controller')) {
            // Route Already found
            return;
        }
        $routeAttrs = $this->matcher->match($request);
        if (count($routeAttrs)) {
            $request->replaceAttribute($routeAttrs);
        }
    }


    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            \Tk\Kernel\KernelEvents::REQUEST => 'onRequest'
        );
    }
    
    
}