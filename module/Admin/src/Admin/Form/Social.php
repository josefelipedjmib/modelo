<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin\Form;

use Doctrine\ORM\EntityManager;
use Zend\Form\Element;

class Social  extends Usuario
{

    public function __construct($name = null, EntityManager $em = null, $options = []) {
        parent::__construct($name, [], $em, $options);
        $id = new Element\Hidden('oauth_uid');
        $this->add($id);
        $rede = new Element\Hidden('oauth_provider');
        $this->add($rede);
        $foto = new Element\Hidden('foto');
        $this->add($foto);
        $perfil = new Element\Hidden('perfil');
        $this->add($perfil);

        $this->removerValidador($this->getInputFilter()->get("email"), '\DoctrineModule\Validator\NoObjectExists');
        $this->removerCampos([
            "id",
            "nome",
            "snome",
            "activate",
            "senha",
            "confirmacao",
            "role"
        ]);

        $this->get("email")
            ->setAttributes(array(
                "disabled" => "disabled"
            )
        );
    }
    
}
