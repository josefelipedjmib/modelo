<?php

namespace AdminSocial\Auth;

use Zend\Authentication\Adapter\AdapterInterface,
    Zend\Authentication\Result;

use Doctrine\ORM\EntityManager;

class AdapterSocial implements AdapterInterface
{
    
    public function __construct(EntityManager $em) 
    {
        $this->em = $em;
    }
    
    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getOauthUid(){
        return $this->oauth_uid;
    }

    public function setOauthUid($oauth_uid){
        $this->oauth_uid = $oauth_uid;
    }

    public function authenticate() 
    {
        $repository = $this->em->getRepository("AdminSocial\Entity\UsuarioSocial");
        $user = $repository->findByOauthUidAndEmail($this->getOauthUid(), $this->getEmail());
        if($user){
            $sql ='select * from usuario where id="'.$user->getUsuario()->getId().'"';
            $stmt = $this->em->getConnection()->prepare($sql);
            $stmt->execute();
            $arr = $stmt->fetch();
            $user = new \Admin\Entity\Usuario($arr);
            return new Result(Result::SUCCESS, array('user'=>$user),array('OK'));
        }else{
            return new Result(Result::FAILURE_CREDENTIAL_INVALID, null, array());
        }
    }

    protected $em;
    protected $email;
    protected $oauth_uid;
}

?>