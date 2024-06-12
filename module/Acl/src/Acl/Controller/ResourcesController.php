<?php

namespace Acl\Controller;

use Base\Controller\CrudController,
    Zend\ServiceManager\ServiceLocatorInterface;

class ResourcesController extends CrudController
{

    public function __construct(ServiceLocatorInterface $serviceLocator){
        $this->serviceLocator = $serviceLocator;
        parent::__construct();
        $this->entity = "Acl\Entity\Resource";
        $this->service = "Acl\Service\Resource";
        $this->form = "Acl\Form\Resource";
        $this->controller = "resources";
        $this->route = "acl-admin/default";
        $this->nome = "recurso";
    }
}
