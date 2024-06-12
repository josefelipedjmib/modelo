<?php

namespace AdminSocial\Entity;

use Doctrine\ORM\EntityRepository;

class UsuarioSocialRepositorio extends EntityRepository 
{
    public function findByOauthUidAndEmail($oauth_uid, $email)
    {
        $user = $this->findOneByOauthUid($oauth_uid);
        if($user){
            if($user->getEmail()!=$email){
                $user = false;
            }
        }
        return $user;
    }
        
    public function findArray()
    {
        $socials = $this->findAll();
        $a = array();
        foreach($socials as $social)
        {
            $a[$social->getId()]['id'] = $social->getId();
            $a[$social->getId()]['usuario'] = $social->getUsuario();
            $a[$social->getId()]['email'] = $social->getEmail();
            $a[$social->getID()]['provedor'] = $social->getOauthProvider();
        }
        
        return $a;
    }

}

?>