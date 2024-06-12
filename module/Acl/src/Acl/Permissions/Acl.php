<?php

namespace Acl\Permissions;

use Zend\ServiceManager\ServiceManager;
use Zend\Permissions\Acl\Acl as ClassAcl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

class Acl extends ClassAcl 
{

    protected $roles;
    protected $resources;
    protected $privileges;
    protected $sm;
    
    public function __construct(ServiceManager $sm, array $roles, array $resources, array $privileges) {
        $this->sm = $sm;
        $this->roles = $roles;
        $this->resources = $resources;
        $this->privileges = $privileges;
        $this->loadRoles();
        $this->loadResources();
        $this->loadPrivileges();
    }

    public function valida($path, $usuario, $privilege=null){
        $i=0;
        $valido = false;
        if(is_object($usuario)){
            while($i<$usuario->getRole()->count() && !$valido){
                if($usuario->getRole()[$i]->getIsAdmin()){
                    $valido = true;
                    return $valido;
                }
                $j = 1;
                if($usuario->getRole()[$i]->getParent())
                    $j = 2;
                while($j>0 && !$valido){
                    $role = '';
                    $roleID = '';
                    if($j==2)
                    {
                        $role = $usuario->getRole()[$i]->getParent()->getNome();
                        $roleID = $usuario->getRole()[$i]->getParent()->getId();
                    }
                    else
                    {
                        $role = $usuario->getRole()[$i]->getNome();
                        $roleID = $usuario->getRole()[$i]->getId();
                    }
                    $query = $this->sm->get("Doctrine\ORM\EntityManager")->createQuery("select distinct re.nome recurso, p.nome privilegio from Acl\Entity\Privilege p join p.resource re join p.role ro where ro=".$roleID);
                    $acl = $this;
                    foreach($query->getResult() as $resultado){
                        if(
                        strpos($path,"/admin/".$resultado["recurso"])===0  ||
                        $path==$resultado["recurso"]
                        ){
                            $resource = $resultado["recurso"];
                            if(empty($privilege))
                                $privilege = $resultado["privilegio"];
                            try{
                                $valido = $acl->isAllowed(
                                    $role,
                                    $resource,
                                    $privilege
                                )? true : false;
                            }
                            catch(\Exception $e)
                            {};
                        }
                    }
                    $j--;
                }
                $i++;
            }
        }
        return $valido;
    }

    public function isAdmin($usuario){
        $i=0;
        while($i<$usuario->getRole()->count()){
            if($usuario->getRole()[$i]->getIsAdmin()){
                return true;
            }
            $i++;
        }
        return false;
    }
    
    protected function loadRoles()
    {
        foreach($this->roles as $role)
        {
            if(!$role->getIsAdmin() && $role->getParent()) 
            {   
                $this->addRole(
                    new Role($role->getNome()),
                    new Role($role->getParent()->getNome())
                );
            }
            else
                $this->addRole (new Role($role->getNome()));

            if($role->getIsAdmin())
                    $this->allow($role->getNome(),array(),array());
        }
    }
    
    protected function loadResources()
    {
        foreach($this->resources as $resource) 
        {
            $this->addResource(new Resource($resource->getNome()));
        }
    }
    
    protected function loadPrivileges()
    {
        foreach($this->privileges as $privilege)
        {
            $this->allow($privilege->getRole()->getNome(), $privilege->getResource()->getNome(),$privilege->getNome());
        }
    }
}
