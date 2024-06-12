<?php

namespace Acl\Controller;

use Base\Controller\CrudController,
    Zend\ServiceManager\ServiceLocatorInterface;

class RolesController extends CrudController
{

    public function __construct(ServiceLocatorInterface $serviceLocator){
        $this->serviceLocator = $serviceLocator;
        parent::__construct();
        $this->entity = "Acl\Entity\Role";
        $this->service = "Acl\Service\Role";
        $this->form = "Acl\Form\Role";
        $this->controller = "roles";
        $this->route = "acl-admin/default";
        $this->nome = "papel";
    }
    
    public function testeAction()
    {
        $acl = $this->getServiceLocator()->get("Acl\Permissions\Acl");
        
        echo $acl->isAllowed("Membro","Noticia","Novo / Modifica Próprio")? "Permitido" : "Negado";
        die;
    }
}
