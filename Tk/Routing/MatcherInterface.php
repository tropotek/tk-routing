<?php
namespace Tk\Routing;

/**
 * Class Exception
 *
 * @author Michael Mifsud <info@tropotek.com>
 * @see http://www.tropotek.com/
 * @license Copyright 2016 Michael Mifsud
 * @notes Adapted from Symfony
 */
interface MatcherInterface  
{
    /**
     * Return true if a path matches a Route object
     *
     * @param \Tk\Request $request
     * @return array
     */
    public function match($request);
    
}
