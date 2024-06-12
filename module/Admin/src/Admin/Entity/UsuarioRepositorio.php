<?php

namespace Admin\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

class UsuarioRepositorio extends EntityRepository 
{
    
    public function getCommonUsers(){
        $rsm = new ResultSetMappingBuilder($this->_em);
        $rsm->addRootEntityFromClassMetadata("\Admin\Entity\Usuario", 'usuario');
        $sql ="SELECT * FROM usuario WHERE id NOT IN (SELECT u.id FROM usuario AS u JOIN usuario_role AS r WHERE u.id=r.usuario_id AND r.`role_id`=0)";
        $query = $this->_em->createNativeQuery($sql,$rsm);
        return $query->getResult();
    }

    public function findByEmailAndPassword($email, $password)
    {
        $user = $this->findOneByEmail($email);
        if($user)
        {
            $hashSenha = $user->encryptPassword($password);
            if($hashSenha == $user->getSenha())
                return $user;
            return "existe";
        }
        else
            return false;
    }
    
    public function findByIdAndPassword($id, $password)
    {
        $user = $this->find($id);
        if($user)
        {
            $hashSenha = $user->encryptPassword($password);
            if($hashSenha == $user->getSenha())
                return $user;
        }
        else
            return false;
    }
    
    public function findByCPFAndPassword($cpf, $password)
    {
        $user = $this->findOneByCpf($cpf);
        if($user)
        {
            $hashSenha = $user->encryptPassword($password);
            if($hashSenha == $user->getSenha())
                return $user;
            return "existe";
        }
        else
            return false;
    }
    
    public function findArray()
    {
        $users = $this->findAll();
        $a = array();
        foreach($users as $user)
        {
            $a[$user->getId()]['id'] = $user->getId();
            $a[$user->getId()]['nomeExibicao'] = $user->getNomeexibicao();
            $a[$user->getId()]['email'] = $user->getEmail();
        }
        
        return $a;
    }

}

?>