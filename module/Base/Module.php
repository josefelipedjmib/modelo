<?php

namespace Base;

use \Zend\EventManager\Event;
use \Zend\Mvc\ModuleRouteListener;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    } 
    
    /**
     * Configure PHP ini settings on the bootstrap event
     * @param Event $e
     */
     
    public function onBootstrap(Event $e) {
        $eventManager        = $e->getApplication()->getEventManager(); 
        $moduleRouteListener = new ModuleRouteListener(); 
        $moduleRouteListener->attach($eventManager); 
        // $phpSettings = \Zend\ServiceManager->getConfig();
        // print_r($phpSettings);
        // exit();
        // if($phpSettings) {
        //     foreach($phpSettings as $key => $value) {
        //         ini_set($key, $value);
        //     }
        // }
    }
    
    /**
    * The getAutoloaderConfig() and getConfig() methods are left out here
     *  for brevity, as they are completely standard.
     */
    
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function getViewHelperConfig()
    {
        return array(
            'factories' => [
                'ServiceLocator' => function ($sm) {
                    return new \Base\View\Helper\ServiceLocator($sm->getServiceLocator()); 
                },
                'Configger' => function ($sm) {
                    return new \Base\View\Helper\Configger($sm->getServiceLocator()->get('config')); 

                }
            ],
            'invokables' => array(
                'Logger' => '\Base\View\Helper\Logger'
            ),
        );
    }
}
