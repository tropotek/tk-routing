<?php
namespace Tk\Routing;

/**
 * Class Exception
 *
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2016 Michael Mifsud
 * @notes Adapted from Symfony
 */
class UrlMatcher implements MatcherInterface  
{

    /**
     * @var RouteCollection
     */
    protected $routeCollection = null;

    /**
     * Matche a route to the request using the path and 
     * 
     * @param RouteCollection $routeCollection
     */
    public function __construct(RouteCollection $routeCollection)
    {
        $this->routeCollection = $routeCollection;
    }
    
    /**
     * Return true if a path matches a Route object
     *
     * @param \Tk\Request $request
     * @return array
     */
    public function match($request)
    {
        // Match request path to the route path
        $uri = $request->getUri();
        $pathinfo = $uri->getRelativePath();
        if ($pathinfo === '') $pathinfo = '/';  // Look for default home path if path is empty, Keep an eye on this one....
        
        /* @var Route $route */
        foreach($this->routeCollection as $name => $route) {
            $compiledRoute = $route->compile();
            
            // check the static prefix of the URL first. Only use the more expensive preg_match when it matches
            if ('' !== $compiledRoute->getStaticPrefix() && 0 !== strpos($pathinfo, $compiledRoute->getStaticPrefix())) {
                continue;
            }

            if (!preg_match($compiledRoute->getRegex(), $pathinfo, $matches)) {
                continue;
            }
            
            $hostMatches = array();
//            if ($compiledRoute->getHostRegex() && !preg_match($compiledRoute->getHostRegex(), $this->context->getHost(), $hostMatches)) {
//                continue;
//            }
            
            // Match the methods allowed
            if (count($route->getValidMethods()) && !in_array($request->getMethod(), $route->getValidMethods())) {
                continue;
            }
            
            $ret = $this->getAttributes($route, $name, array_replace($matches, $hostMatches));
            return $ret;
        }
        return [];
    }

    /**
     * Returns an array of values to use as request attributes.
     *
     * As this method requires the Route object, it is not available
     * in matchers that do not have access to the matched Route instance
     * (like the PHP and Apache matcher dumpers).
     *
     * @param Route  $route      The route we are matching against
     * @param string $name       The name of the route
     * @param array  $attributes An array of attributes from the matcher
     * @return array An array of parameters
     */
    protected function getAttributes(Route $route, $name, array $attributes)
    {
        $attributes['_route'] = $name;
        $attributes['_controller'] = $route->getController();
        return $this->mergeDefaults($attributes, $route->getAttributes()->all());
    }

    /**
     * Get merged default parameters.
     *
     * @param array $params   The parameters
     * @param array $defaults The defaults
     * @return array Merged default parameters
     */
    protected function mergeDefaults($params, $defaults)
    {
        foreach ($params as $key => $value) {
            if (!is_int($key)) {
                $defaults[$key] = $value;
            }
        }
        return $defaults;
    }
    
}
