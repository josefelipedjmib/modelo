<?php

namespace Admin\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\Persistence\ObjectManager;

use Admin\Entity\Usuario;

class LoadUsuario extends AbstractFixture
{
    
    public function load(ObjectManager $manager) {
        // $user = new User();
        // $user->setNome("José Felipe")
        //         ->setEmail("josefelipe@yahoo.com.br")
        //         ->setPassword(123456)
        //         ->setActive(true);
        
        // $manager->persist($user);
        
        // $user = new User();
        // $user->setNome("Admin")
        //         ->setEmail("admin.teste@djmib.com.br")
        //         ->setPassword(123456)
        //         ->setActive(true);
        
        // $manager->persist($user);
        
        // $manager->flush();
    }

}
