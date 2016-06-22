<?php
namespace Tk\Routing;

/**
 * Use this matcher to match static paths in the template directory
 * 
 * This match is helpful to render static pages in the template path,
 *
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2016 Michael Mifsud
 */
class StaticMatcher implements MatcherInterface  
{
    /**
     * @var string
     */
    protected $templatePath = '';

    /**
     * @var string
     */
    protected $staticController = '';
    

    /**
     * UrlMatcher constructor.
     *
     * @param string $templatePath
     * @param string $staticController (eg: '\path\To\Controller::doMethod')
     * @throws \Tk\Exception
     */
    public function __construct($templatePath, $staticController)
    {
        if (!is_dir($templatePath)) {
            throw new \Tk\Exception('Template path not found');
        }
        $this->templatePath = $templatePath;
        $this->staticController = $staticController;
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
        
        $staticPath = $this->templatePath . $pathinfo;
        $name = str_replace('.html', '', basename($pathinfo));
        
        if (is_file($staticPath)) {
            return ['_route' => $name, '_controller' => $this->staticController, '_staticPath' => $staticPath];
        }
        return [];
    }
    
}
