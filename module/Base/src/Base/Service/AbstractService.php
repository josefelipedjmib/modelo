<?php

namespace Base\Service;

use Zend\ServiceManager\ServiceManager,
    Doctrine\ORM\EntityManager;
use Zend\Stdlib\Hydrator;

abstract class AbstractService 
{
    
    public function __construct(ServiceManager $sm) 
    {
        $this->sm = $sm;
        $this->em = $sm->get('Doctrine\ORM\EntityManager');
    }
    
    public function insert(array $data)
    {
        $entity = new $this->entity($data);
        return $this->persistTransactional($entity);
    }
    
    public function update(array $data)
    {
        $entity = $this->em->getReference($this->entity, $data['id']);
        (new Hydrator\ClassMethods())->hydrate($data, $entity);
        return $this->persistTransactional($entity);
    }
    
    public function delete($id)
    {
        $entity = $this->em->getReference($this->entity, $id);
        if($entity)
        {
            $this->em->remove($entity);
            $this->em->flush();
            return $id;
        }
        return $entity;
    }
    
    public function logStatus($acao,$status){
        $usuarioSistema = '000001';
        $usuario = false;
        $logado = $this->usuarioLogado();
        if($logado){
            $usuario = $this->em->find(
                "Admin\Entity\Usuario",
                $logado->getId()
            );
        }
        if(!$usuario){
            $usuario = $this->em->find(
                "Admin\Entity\Usuario",
                $usuarioSistema
            );
        }
        $registro = new \Admin\Entity\Logsystem();
        $registro->setAcao($acao);
        $registro->setStatus($status);
        $registro->setUsuario($usuario);
        $ip = \Base\Mib\URL::getInstance()->getIPTratado();
        $registro->setIpa($ip[0]);
        $registro->setIpb($ip[1]);
        $registro->setIpc($ip[2]);
        $registro->setIpd($ip[3]);
        $this->em->flush();
    }

    public function persistTransactional($entity)
    {
        if($entity){
            $this->entityObject = $entity;
            $this->em->transactional(function($em)
            {
                $em->persist($this->entityObject);
                $em->flush();
            });
            unset($this->entityObject);
            return $entity;
        }
        return false;
    }

    public function usuarioLogado(){
        $usuarioIdentidade = $this->sm->get('ViewHelperManager')->get('UsuarioIdentidade');
        return $usuarioIdentidade("Admin");
    }

    /**
     *
     * @var EntityManager
     */
    protected $sm;
    protected $em;
    protected $entity;
    protected $entityObject;
    
}
