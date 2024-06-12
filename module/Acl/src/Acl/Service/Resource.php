<?php

namespace Acl\Service;

use Base\Service\AbstractService;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\Hydrator;

class Resource extends AbstractService
{
    public function __construct(ServiceManager $sm) {
        parent::__construct($sm);
        $this->entity = "Acl\Entity\Resource";
    }
    
    public function insert(array $data)
    {
        $entity = new $this->entity($data);
        
        $this->em->persist($entity);
        $this->em->flush();
        return $entity;
    }
    
    public function update(array $data)
    {
        $entity = $this->em->getReference($this->entity, $data['id']);
        (new Hydrator\ClassMethods())->hydrate($data, $entity);
        
        $this->em->persist($entity);
        $this->em->flush();
        return $entity;
    }
    
    
}
