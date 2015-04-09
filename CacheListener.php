<?php
/**
 * Cache-Listner, to handle the cache on the url basis
 * @author Tarun Singhal
 * @date 27 March 2015
 */
namespace PageCache;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;

class CacheListener extends AbstractListenerAggregate
{

    protected $listeners = array();

    protected $cacheService;

    public function __construct(\Zend\Cache\Storage\Adapter\Filesystem $cacheService)
    {
        // We store the cache service generated by Zend\Cache from the service manager
        $this->cacheService = $cacheService;
    }

    /**
     * attach listners
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        // The AbstractListenerAggregate we are extending from allows us to attach our event listeners
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, array(
            $this,
            'getCache'
        ), - 1000);
        $this->listeners[] = $events->attach(MvcEvent::EVENT_RENDER, array(
            $this,
            'saveCache'
        ), - 10000);
    }

    /**
     * Get Cache from the called event
     * @param MvcEvent $event
     */
    public function getCache(MvcEvent $event)
    {
        $match = $event->getRouteMatch();
        
        // is valid route?
        if (! $match) {
            return;
        }
        // does our route have the cache flag set to true?
        if ($match->getParam('cache')) {
            
            $cacheKey = $this->genCacheName($match);
            // get the cache page for this route
            $data = $this->cacheService->getItem($cacheKey);
            
            // ensure we have found something valid
            if ($data !== null) {
                $response = $event->getResponse();
                $response->setContent($data);
                // return $response; //used to render data directly without instantiate of controllers
            }
        }
    }

    /**
     * To save the cache at server end
     * @param MvcEvent $event
     */
    public function saveCache(MvcEvent $event)
    {
        $match = $event->getRouteMatch();
        
        // is valid route?
        if (! $match) {
            return;
        }
        
        // does our route have the cache flag set to true?
        if (! $match->getParam('cache')) {
            $response = $event->getResponse();
            $data = $response->getContent();
            
            $cacheKey = $this->genCacheName($match);
            $this->cacheService->setItem($cacheKey, $data);
        }
    }
    
    /**
     * To get the cache name on respective cached url
     * @param object $match
     * @return string
     */
    protected function genCacheName($match)
    {
        return 'cache_' . str_replace('/', '-', $match->getMatchedRouteName());
    }
}