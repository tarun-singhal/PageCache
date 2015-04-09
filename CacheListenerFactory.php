<?php 
/**
 * CacheListner factory 
 */
namespace PageCache;
 
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
 
class CacheListenerFactory implements FactoryInterface {
 
    public function createService(ServiceLocatorInterface $serviceLocator) {
        return new CacheListener($serviceLocator->get('Zend\Cache'));
    }
}