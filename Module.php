<?php
/**
 *@desc  Module manager
 *@author Tarun
 *@version 1.0
 *@date-created 27 March, 2015
 */
namespace PageCache;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{

    public function onBootstrap(MvcEvent $event)
    {
        
        $eventManager        = $event->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        // get the cache listener service
        $cacheListener = $event->getApplication()->getServiceManager()->get('CacheListener');
        // attach the listeners to the event manager
        $event->getApplication()->getEventManager()->attach($cacheListener);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__
                )
            )
        );
    }
    
}
