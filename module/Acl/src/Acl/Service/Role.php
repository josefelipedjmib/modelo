<?php

namespace Acl\Service;

use Base\Service\AbstractService;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\Hydrator;

class Role extends AbstractService
{

    public function __construct(ServiceManager $sm) {
        parent::__construct($sm);
        $this->entity = "Acl\Entity\Role";
    }
    
    public function insert(array $data)
    {
        $entity = new $this->entity($data);
        
        if($data['parent'])
        {
            $parent = $this->em->getReference($this->entity, $data['parent']);
            $entity->setParent($parent);
        }
        else 
            $entity->setParent(null);
        
        $this->em->persist($entity);
        $this->em->flush();
        return $entity;
    }
    
    public function update(array $data)
    {
        if(empty($data['id']))
            return false;
        $entity = $this->em->getReference($this->entity, $data['id']);
        (new Hydrator\ClassMethods())->hydrate($data, $entity);
        
        if($data['parent'])
        {
            $parent = $this->em->getReference($this->entity, $data['parent']);
            $entity->setParent($parent);
        }
        else 
            $entity->setParent(null);
        
        $this->em->persist($entity);
        $this->em->flush();
        return $entity;
    }

    public function delete($id)
    {
        if(empty($id))
            return false;
        return parent::delete($id);
    }
}
