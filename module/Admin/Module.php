<?php

namespace Admin;

// use \Zend\EventManager\Event;
// use \Zend\Mvc\ModuleRouteListener;

use Zend\Mvc\MvcEvent;

use Admin\Auth\Adapter as AuthAdapter,
    Admin\Enum;
    
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
    
    public function init(ModuleManager $moduleManager)
    {
        $sharedEvents = $moduleManager->getEventManager()->getSharedManager();
        
        $sharedEvents->attach("Zend\Mvc\Controller\AbstractActionController", 
                MvcEvent::EVENT_DISPATCH,
                array($this,'validacaoInicial'),100);
    }

    public function validacaoInicial($e){
        $this->validaAuth($e);
    }

    public function validaAuth($e)
    {   
        $controller = $e->getTarget();
        $path = $controller->getRequest()->getUri()->getPath();
        if(strpos($path,"/admin")===0){
            $page = true;

            $serviceManager = $e->getApplication()->getServiceManager();
            $auth = new AuthenticationService;
            $auth->setStorage(new SessionStorage("Admin"));
            
            if(
                $auth->hasIdentity() &&
                is_object($auth->getIdentity()) &&
                strpos($path,"/sair/")!==0
            ){
                $usuario = $serviceManager->get("Doctrine\ORM\EntityManager")->find(
                    "Admin\Entity\Usuario",
                    $auth->getIdentity()->getId()
                );
                if($usuario){
                    $acl = $serviceManager->get("Acl\Permissions\Acl");
                    $_SESSION['Admin']['isAdmin'] = $acl->isAdmin($usuario);
                    if(!$usuario->getActivate()){
                        $page = false;
                    }elseif(
                        strpos($path,"/admin")===0 &&
                        !($path=="/admin/"||$path=="/admin"||strpos($path,"/admin/conta")===0)
                    ){
                        $page = $acl->valida($path, $usuario);
                    }
                }else{
                    $page=false;
                }
            }
            // // Verifica status de logado e redireciona
            $matchedRoute = $controller->getEvent()->getRouteMatch()->getMatchedRouteName();
            if(
                (!$auth->hasIdentity() &&
                    (
                        strpos($matchedRoute,"admin-admin")===0 ||
                        strpos($matchedRoute,"acl-admin")===0 ||
                        false
                        // $matchedRoute == "admin-admin" OR
                        // $matchedRoute == "admin-admin/paginator" or default
                    )
                ) ||
                !$page
            )
            return $controller->redirect()->toRoute("admin-logout");
        }
    }

    public function getServiceConfig()
    {
        
        return array(
            'factories' => array(
              
                'Admin\Service\Auth' => function($sm) {
                    return new Service\Auth($sm);
                },
                'Admin\Service\Usuario' => function($sm) {
                        return new Service\Usuario($sm);
                },

                'Admin\Auth\Adapter' => function($sm){
                        return new AuthAdapter(
                            $sm
                        );
                },
                'Admin\Form\Usuario' => function($sm){
                        $em = $sm->get('Doctrine\ORM\EntityManager');
                        $repo = $em->getRepository('Acl\Entity\Role');
                        $papel = $repo->fetchParent();
                        unset($papel[0]);
                    
                        $form = new Form\Usuario(
                            "usuario",
                            $papel,
                            $em
                        );

                        return $form;
                },
                'Admin\Form\Endereco' => function($sm){
                        $form = new Form\Endereco(
                            "enderecoform"
                        );
                        return $form;
                },
                'Admin\Form\Telefone' => function($sm){
                        $form = new Form\Telefone(
                            "telefoneform"
                        );
                        return $form;
                },
            )  
        );
        
    }

    public function getControllerConfig(){
        return [
            'factories' => [
                'Admin\Controller\Auth' => function($sm){
                    $authController = new Controller\AuthController($sm->getServiceLocator());
                    return $authController;
                },
                'Admin\Controller\Configuration' => function($sm){
                    $configurationController = new Controller\ConfigurationController($sm->getServiceLocator());
                    return $configurationController;
                },
                'Admin\Controller\Conta' => function($sm){
                    $contaController = new Controller\ContaController($sm->getServiceLocator());
                    return $contaController;
                },
                'Admin\Controller\Index' => function($sm){
                    $indexController = new Controller\IndexController($sm->getServiceLocator());
                    return $indexController;
                },
                'Admin\Controller\Usuarios' => function($sm){
                    $usuariosController = new Controller\UsuariosController($sm->getServiceLocator());
                    return $usuariosController;
                },
            ]
        ];
    }
    
    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
                'UsuarioIdentidade' => 'Admin\View\Helper\UsuarioIdentidade'
            )
        );
    }

}
