<?php

namespace Admin\Auth;

use Zend\Authentication\Adapter\AdapterInterface,
    Zend\Authentication\Result,
    Zend\ServiceManager\ServiceLocatorInterface;

use Doctrine\ORM\EntityManager;

class Adapter implements AdapterInterface
{
    
    public function __construct(ServiceLocatorInterface $sm) 
    {
        $this->em = $sm->get('Doctrine\ORM\EntityManager');
        $config = $sm->get('config');
        $this->useSocial = $config["usaprovedorsocial"];
        $this->tipoLogin = $config["tipodelogin"];
    }
    
    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function authenticate() 
    {
        $repository = $this->em->getRepository("Admin\Entity\Usuario");
        if($this->tipoLogin == 'cpf'){
            $user = $repository->findByCPFAndPassword($this->getUsername(),$this->getPassword());
        }else{
            $user = $repository->findByEmailAndPassword($this->getUsername(),$this->getPassword());
        }
        
        if(!$user && $this->useSocial){
            $sql ='select id from usuario where id in (select usuario from usuariosocial where email="'.$this->getUsername().'")';
            $stmt = $this->em->getConnection()->prepare($sql);
            $stmt->execute();
            $arr = $stmt->fetch();
            if(!empty($arr)){
                $user = $repository->findByIdAndPassword($arr["id"],$this->getPassword());
            }
        }
        if(is_object($user))
            return new Result(Result::SUCCESS, array('user'=>$user),array('OK'));
        else
            return new Result(Result::FAILURE_CREDENTIAL_INVALID, null, array());
    }

    protected $em;
    protected $username;
    protected $password;
    protected $useSocial = false;
    protected $tipoLogin = "email";

}

?>