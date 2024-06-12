<?php

namespace AdminSocial;

// use \Zend\EventManager\Event;
// use \Zend\Mvc\ModuleRouteListener;

use Zend\Mvc\MvcEvent;

use Admin\Auth\Adapter as AuthAdapter,
    AdminSocial\Auth\AdapterSocial as AuthAdapterSocial;
    
use Zend\Authentication\AuthenticationService,
    Zend\Authentication\Storage\Session as SessionStorage;

use Zend\ModuleManager\ModuleManager;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    } 
         
    
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

    public function getServiceConfig()
    {
        
        return array(
            'factories' => array(
              
                'AdminSocial\Service\UsuarioSocial' => function($sm) {
                        return new Service\UsuarioSocial($sm);
                },

                'AdminSocial\Auth\AdapterSocial' => function($sm){
                        return new AuthAdapterSocial(
                            $sm->get('Doctrine\ORM\EntityManager')
                        );
                },
            )  
        );
        
    }

    public function getControllerConfig(){
        return [
            'factories' => [
                'AdminSocial\Controller\Auth' => function($sm){
                    $authController = new Controller\AuthController($sm->getServiceLocator());
                    return $authController;
                },
            ]
        ];
    }

    private $controller;

}
