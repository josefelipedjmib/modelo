<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
 
namespace AdminSocial\Service;

use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\Hydrator;
use Base\Mail\Mail;

class UsuarioSocial extends Usuario
{

    
    public function __construct(ServiceManager $sm) 
    {
        parent::__construct($sm, true);
        $this->cfg = $sm->get('config');
        $this->view = $sm->get('View');
        $this->entity = "AdminSocial\Entity\Usuariosocial";
        $this->entityes = [
            "Admin\Entity\Usuario",
            $this->entity
        ];
    }
    
    public function insert(array $data) {
        $logado = $this->usuarioLogado();
        $existe = $this->existeEmail($data["email"],$this->entityes);
        if($existe!==false){
            return $existe;
        }
        $entity = false;
        if($logado){
            $entity = $this->em->getReference($this->entityes[0], $logado->getId());
        }else{
            $entity = new $this->entityes[0]([
                "nomeexibicao" => $data["nomeexibicao"],
                "email" => $data["email"]
            ]);
            $repo = $this->em->getRepository("Acl\Entity\Role");
            $entity->addRole($repo->findByNome("membro")[0]);
        }
        $social = new $this->entityes[1]($data);
        $social->setUsuario($entity);
        $entity->addSociais($social);
        $entity->setActivate(true);
        if($logado){
            $this->em->persist($entity);
            $this->em->flush();
        }else{
            $entity = $this->persistTransactional($entity);
        }
        
        if($entity)
        {
            if($logado){
                $this->logStatus(
                    "UsuarioSocial - adicionado",
                    "UsuárioSocial adicionado um provedor\n ID: ".$entity->getId()."\n
                    E-mail: ".$social->getEmail()."\n
                    Provedor: ".$social->getOauthProvider()."\n
                    OAuthUID: ".$social->getOauthUid()
                );
                $this->enviaEmail(
                    $entity,
                    "Provedor conectado na conta",
                    'add-social'
                );
            }else{
                $this->logStatus(
                    "UsuarioSocial - cadastrado ",
                    "UsuárioSocial cadastrado por provedor \n
                    ID: ".$entity->getId()."
                    E-mail: ".$social->getEmail()."\n
                    Provedor: ".$social->getOauthProvider()."\n
                    OAuthUID: ".$social->getOauthUid()
                );
                $this->enviaEmail(
                    $entity,
                    "Confirmação de Cadastro",
                    'add-user'
                );
            }
        }
        return $entity;
    }
    
    public function update(array $data)
    {
        return false;
    }
    
    public function delete($oauth_uid)
    {   
        $entity = $this->em->getRepository($this->entity)->findByOauthUid($oauth_uid);
        if(count($entity)==1){
            $entity = $entity[0];
        }else{
            $entity = false;
        }
        if($entity)
        {
            $this->em->remove($entity);
            $this->em->flush();
            $this->logStatus(
                "UsuarioSocial - apagado",
                "UsuárioSocial apagado \n
                ID: ".$entity->getUsuario()->getId()."\n
                E-mail: ".$entity->getEmail()."\n
                Provedor: ".$entity->getOauthProvider()."\n
                OAuthUID: ".$oauth_uid
            );
            return true;
        }
        return $entity;
    }
    
}
