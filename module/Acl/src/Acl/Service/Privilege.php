<?php

namespace Acl\Service;

use Base\Service\AbstractService;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\Hydrator;

class Privilege extends AbstractService
{
    public function __construct(ServiceManager $sm) {
        parent::__construct($sm);
        $this->entity = "Acl\Entity\Privilege";
    }
    
    public function insert(array $data)
    {
        $entity = new $this->entity($data);
        
        $role = $this->em->getReference("Acl\Entity\Role",$data['role']);
        $entity->setRole($role); // Injetando entidade carregada
        
        $resource = $this->em->getReference("Acl\Entity\Resource",$data['resource']);
        $entity->setResource($resource); // Injetando entidade carregada
        
        $this->em->persist($entity);
        $this->em->flush();
        return $entity;
    }
    
    public function update(array $data)
    {
        $entity = $this->em->getReference($this->entity, $data['id']);
        (new Hydrator\ClassMethods())->hydrate($data, $entity);
        
        $role = $this->em->getReference("Acl\Entity\Role",$data['role']);
        $entity->setRole($role); // Injetando entidade carregada
        
        $resource = $this->em->getReference("Acl\Entity\Resource",$data['resource']);
        $entity->setResource($resource); // Injetando entidade carregada
        
        $this->em->persist($entity);
        $this->em->flush();
        return $entity;
    }
    
}
