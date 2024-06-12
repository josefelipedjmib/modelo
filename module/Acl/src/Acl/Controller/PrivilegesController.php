<?php

namespace Acl\Controller;

use Base\Controller\CrudController,
    Zend\ServiceManager\ServiceLocatorInterface;

class PrivilegesController extends CrudController
{

    public function __construct(ServiceLocatorInterface $serviceLocator){
        $this->serviceLocator = $serviceLocator;
        parent::__construct();
        $this->entity = "Acl\Entity\Privilege";
        $this->service = "Acl\Service\Privilege";
        $this->form = "Acl\Form\Privilege";
        $this->controller = "privileges";
        $this->route = "acl-admin/default";
        $this->nome = "privilégio";
    }
}
